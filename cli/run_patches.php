<?php
/**
 * CLI patch runner — applies pending SQL patches at container/CLI startup.
 *
 * Usage:  php cli/run_patches.php
 *
 * Called automatically by docker-entrypoint.sh after the config is written and
 * the database is accepting connections.  Safe to run manually too — already-applied
 * patches are skipped, so re-running is idempotent.
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

// Config loader
include_once './include/class/ConfigLoader.php';

$config = is_file('./config/custom.config.php')
    ? ConfigLoader::load('./config/custom.config.php')
    : ConfigLoader::load('./config/config.php');

// Adapter string used throughout sql_queries / sql_patches ('mysql', 'pgsql', 'sqlite')
$db_server = substr($config->database->adapter, 4);

date_default_timezone_set($config->phpSettings->date->timezone ?? 'UTC');
error_reporting(E_ERROR);
ini_set('display_errors', '0');

// Set up logger — mirrors how init.php does it; writes to the same si.log the app uses
include_once './include/class/LegacyLogger.php';
$logFile = './tmp/log/si.log';
if (!is_file($logFile)) {
    @touch($logFile);
}
$logger = new LegacyLogger($logFile);

// functions.php: htmlsafe(), simpleInvoicesError() — both called by sql_queries.php
include_once './include/functions.php';

// sql_queries.php: opens the DB connection (sets $dbh), defines dbQuery(),
// run_sql_patch(), initialise_sql_patch(), check_sql_patch(), checkMysqlIndexExists(), …
include_once './include/sql_queries.php';

// sql_patches.php: defines the $patch array.
// Must be included AFTER sql_queries.php because newer patches call checkMysqlIndexExists()
// at parse time (inside switch blocks that run on include).
include_once './include/sql_patches.php';

// ---------------------------------------------------------------------------
// CLI-friendly patch runner
// ---------------------------------------------------------------------------

// Write to both stdout (docker logs) and si.log
$log = static function (string $message, string $level = 'info') use ($logger): void {
    echo $message . "\n";
    $logger->log($message, $level);
};

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
