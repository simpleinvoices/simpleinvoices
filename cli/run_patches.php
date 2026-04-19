<?php
/**
 * CLI patch runner — applies pending SQL patches at container/CLI startup.
 *
 * Usage:  php cli/run_patches.php
 *
 * Called automatically by docker-entrypoint.sh after the config is written and
 * the database is accepting connections.  Safe to run manually too — already-applied
 * patches are skipped, so re-running is idempotent.
 *
 * Does nothing until the database looks install-ready: `si_biller` exists and
 * either (a) installer essential data is present for domain 1 (preferences +
 * custom_fields), matching index.php / init.php, or (b) `si_sql_patchmanager`
 * already has rows (existing deployment / restored DB) so pending patches can
 * be applied even if the bootstrap check fails.
 */

// Must run from app root so all relative paths (config, includes, vendor) resolve correctly.
chdir(dirname(__DIR__));

// Mirrors the include-path setup in init.php
set_include_path(get_include_path()
    . PATH_SEPARATOR . './include/class'
    . PATH_SEPARATOR . './library/'
    . PATH_SEPARATOR . './include/'
);

// Composer autoloader (Blade, mPDF, etc.)
require_once './vendor/autoload.php';

// TB_PREFIX, LOGGING, BROWSE, …
define('BROWSE', true);   // satisfies the checkLogin() guard in module files
include_once './config/define.php';

// ---------------------------------------------------------------------------
// Config
// ---------------------------------------------------------------------------
include_once './include/class/ConfigLoader.php';

$config = is_file('./config/custom.config.php')
    ? ConfigLoader::load('./config/custom.config.php', '')
    : ConfigLoader::load('./config/config.php', '');

// Adapter string used throughout sql_queries / sql_patches ('mysql', 'pgsql', 'sqlite')
$db_server = substr($config->database->adapter, 4);

date_default_timezone_set($config->phpSettings->date->timezone ?? 'UTC');
#error_reporting(E_ALL);
#ini_set('display_errors', '1');

// $config->extension is iterated by language.php; default to empty so the foreach
// is a no-op rather than a fatal when extensions haven't been loaded from the DB yet.
if (!isset($config->extension) || !$config->extension) {
    $config->extension = ConfigData::fromArray([]);
}

// ---------------------------------------------------------------------------
// Core includes — order matters; mirrors init.php
// ---------------------------------------------------------------------------

// htmlsafe(), simpleInvoicesError(), filenameEscape() — used by sql_queries.php
include_once './include/init_pre.php';
include_once './include/functions.php';

// Autoloader for include/class/*.php (invoice, customer, product, biller, …).
// Mirrors the spl_autoload_register in init.php so any class referenced inside
// sql_patches.php at include-time is resolved automatically.
spl_autoload_register(function (string $class_name): void {
    $path = "./include/class/{$class_name}.php";
    if (is_file($path)) {
        include_once $path;
    }
});

// db.php: db class used directly by getSystemDefaults() via `new db()`.
// Must be explicit — the autoloader above would find it, but $config must already
// be in scope when the constructor runs, so eager-load it here.
include_once './include/class/db.php';

// domain_id: lives in include/class/domain/id.php — autoloader maps to wrong path.
include_once './include/class/domain/id.php';

// $auth_session stub: sql_queries.php reads $auth_session->id unconditionally in
// dbLogger() before checking $can_log, so a null value causes a warning/fatal.
$auth_session = (object) ['id' => 0, 'domain_id' => 1];

// $logger stub: invoice::max() calls $logger->log() as a global. Provide a no-op
// so it doesn't fatal — we don't need the log output in the CLI runner.
$logger = new class {
    public function log(string $message, string $level = ''): void {}
    public function info(string $message, array $context = []): void {}
    public function error(string $message, array $context = []): void {}
};

// sql_queries.php: opens the DB connection ($dbh), defines dbQuery(), run_sql_patch(),
// initialise_sql_patch(), check_sql_patch(), checkTableExists(), checkFieldExists(),
// checkMysqlIndexExists(), getSystemDefaults(), getNumberOfDonePatches(), etc.
include_once './include/sql_queries.php';

// language.php: sets $language (used by sql_patches.php patch 207) and $LANG.
// Must come AFTER sql_queries.php because it calls checkTableExists() at include-time.
include_once './include/language.php';

// sql_patches.php: builds the $patch array, executing DB queries and class
// instantiations at include-time (getNumberOfDonePatches, getSystemDefaults,
// checkTableExists, checkFieldExists, new invoice(), $language, $config->*, …).
// All of the above must be in scope before this line.
include_once './include/sql_patches.php';

// ---------------------------------------------------------------------------
// CLI-friendly patch runner
// ---------------------------------------------------------------------------

$log = static function (string $message): void {
    echo $message . "\n";
};

$install_tables_exists = checkTableExists(TB_PREFIX . 'biller');
$has_patch_history     = false;
if ($install_tables_exists && checkTableExists(TB_PREFIX . 'sql_patchmanager')) {
    try {
        $row = dbQuery('SELECT COUNT(*) AS c FROM ' . TB_PREFIX . 'sql_patchmanager')->fetch(PDO::FETCH_ASSOC);
        $has_patch_history = $row && (int) ($row['c'] ?? 0) > 0;
    } catch (Exception $e) {
        $has_patch_history = false;
    }
}
// Web installer “essential” bootstrap — OR any existing patch history (upgrades / Docker volumes with real data).
$installer_bootstrap_ok = checkDataExists();
$ready_for_patches      = $install_tables_exists && ($installer_bootstrap_ok || $has_patch_history);
if (!$install_tables_exists || !$ready_for_patches) {
    $log('[patches] Skipping — database not ready (need si_biller plus installer essential data, or at least one row in si_sql_patchmanager).');
    exit(0);
}
if ($has_patch_history && !$installer_bootstrap_ok) {
    $log('[patches] Detected existing patch history — applying pending migrations (installer bootstrap check skipped).');
}

$total   = max(array_keys($patch));
$applied = 0;
$skipped = 0;

// Ensure the patchmanager tracking table exists (fresh install path)
if ($db_server === 'pgsql') {
    $tableCheck = "SELECT 1 FROM pg_tables WHERE schemaname = current_schema() AND tablename = '" . TB_PREFIX . "sql_patchmanager'";
} elseif ($db_server === 'sqlite') {
    $tableCheck = "SELECT 1 FROM sqlite_master WHERE type='table' AND name='" . TB_PREFIX . "sql_patchmanager'";
} else {
    $tableCheck = "SHOW TABLES LIKE '" . TB_PREFIX . "sql_patchmanager'";
}

$rows = dbQuery($tableCheck)->fetchAll();

if (count($rows) === 0) {
    $log('[patches] Initialising sql_patchmanager table...');
    initialise_sql_patch();
}

// Iterate every patch; run_sql_patch() skips ones already recorded
for ($i = 0; $i <= $total; $i++) {
    if (!isset($patch[$i])) {
        continue;
    }

    $result = run_sql_patch($i, $patch[$i]);

    if (($result['result'] ?? '') === 'done') {
        $applied++;
        $log("[patches] Applied #{$i}: {$patch[$i]['name']}");
    } else {
        $skipped++;
    }
}

if ($applied > 0) {
    $log("[patches] Done — {$applied} patch(es) applied, {$skipped} already up to date.");
} else {
    $log("[patches] Database already up to date ({$skipped} patches checked).");
}
