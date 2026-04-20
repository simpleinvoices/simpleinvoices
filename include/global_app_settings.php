<?php

/**
 * Installation-wide app branding (header/footer) stored in si_global_config.
 * Not domain-scoped. Editable only by the administrator role (see modules/admin/app_settings.php).
 */

declare(strict_types=1);

/**
 * Default branding when the table is empty (installer / pre-migration).
 *
 * @return array<string, string>
 */
function si_global_config_seed_values(): array
{
    return [
        'app_name' => 'Simple Invoices',
        'app_logo' => '',
        'app_website' => 'http://www.simpleinvoices.org',
        'app_website_label' => 'Website',
        'app_footer_link1_label' => 'Simple Invoices',
        'app_footer_link1_url' => 'http://www.simpleinvoices.org',
        'app_footer_link2_label' => 'Forum',
        'app_footer_link2_url' => 'http://www.simpleinvoices.org/+',
        'app_footer_link3_label' => 'Blog',
        'app_footer_link3_url' => 'http://www.simpleinvoices.org/blog',
        'app_footer_link4_label' => 'Support',
        'app_footer_link4_url' => 'http://www.simpleinvoices.org/forum',
        'app_footer_text' => 'Thank you for using',
    ];
}

/**
 * Map DB row names to $config->app keys (same as legacy INI keys without the "app." prefix).
 *
 * @return array<string, string>
 */
function si_global_config_db_to_config_keys(): array
{
    return [
        'app_name' => 'name',
        'app_logo' => 'logo',
        'app_website' => 'website',
        'app_website_label' => 'website_label',
        'app_footer_link1_label' => 'footer_link1_label',
        'app_footer_link1_url' => 'footer_link1_url',
        'app_footer_link2_label' => 'footer_link2_label',
        'app_footer_link2_url' => 'footer_link2_url',
        'app_footer_link3_label' => 'footer_link3_label',
        'app_footer_link3_url' => 'footer_link3_url',
        'app_footer_link4_label' => 'footer_link4_label',
        'app_footer_link4_url' => 'footer_link4_url',
        'app_footer_text' => 'footer_text',
    ];
}

/**
 * Merge DB global app settings into $config->app (DB wins over INI; defaults used when a row is absent).
 */
function mergeGlobalAppSettingsIntoConfig(ConfigData $config): void
{
    global $install_tables_exists;

    $map = si_global_config_db_to_config_keys();
    $defaults = si_global_config_seed_values();

    $rows = [];
    if (!empty($install_tables_exists) && function_exists('checkTableExists') && checkTableExists(TB_PREFIX . 'global_config')) {
        $sth = dbQuery('SELECT name, value FROM ' . TB_PREFIX . 'global_config');
        if ($sth) {
            $fetched = $sth->fetchAll(PDO::FETCH_KEY_PAIR);
            $rows = is_array($fetched) ? $fetched : [];
        }
    }

    $app = [];
    foreach ($map as $dbName => $cfgKey) {
        $app[$cfgKey] = $defaults[$dbName] ?? '';
        $iniVal = $config->app?->{$cfgKey} ?? null;
        if ($iniVal !== null && (string) $iniVal !== '') {
            $app[$cfgKey] = (string) $iniVal;
        }
        if (array_key_exists($dbName, $rows)) {
            $app[$cfgKey] = (string) $rows[$dbName];
        }
    }

    $config->app = ConfigData::fromArray($app);
}

function si_global_config_upsert(string $name, string $value): void
{
    global $db_server;
    $t = TB_PREFIX . 'global_config';

    if ($db_server === 'mysql') {
        dbQuery(
            "INSERT INTO `{$t}` (`name`, `value`) VALUES (:name, :value) ON DUPLICATE KEY UPDATE `value` = :value2",
            ':name',
            $name,
            ':value',
            $value,
            ':value2',
            $value
        );
        return;
    }

    if ($db_server === 'pgsql') {
        dbQuery(
            "INSERT INTO {$t} (name, value) VALUES (:name, :value) ON CONFLICT (name) DO UPDATE SET value = EXCLUDED.value",
            ':name',
            $name,
            ':value',
            $value
        );
        return;
    }

    // SQLite
    dbQuery(
        "INSERT OR REPLACE INTO {$t} (name, value) VALUES (:name, :value)",
        ':name',
        $name,
        ':value',
        $value
    );
}

function si_patch342_global_config(): void
{
    global $db_server;
    $t = TB_PREFIX . 'global_config';

    if ($db_server === 'mysql') {
        dbQuery("CREATE TABLE IF NOT EXISTS `{$t}` (
            `name` varchar(64) NOT NULL,
            `value` text,
            PRIMARY KEY (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    } elseif ($db_server === 'pgsql') {
        dbQuery("CREATE TABLE IF NOT EXISTS {$t} (
            name varchar(64) NOT NULL PRIMARY KEY,
            value text
        )");
    } else {
        dbQuery("CREATE TABLE IF NOT EXISTS {$t} (
            name varchar(64) NOT NULL PRIMARY KEY,
            value text
        )");
    }

    foreach (si_global_config_seed_values() as $name => $value) {
        si_global_config_upsert($name, $value);
    }
}

/**
 * @return array<string, string>
 */
function getGlobalAppSettingsForAdminForm(): array
{
    $defaults = si_global_config_seed_values();
    if (!checkTableExists(TB_PREFIX . 'global_config')) {
        return $defaults;
    }
    $sth = dbQuery('SELECT name, value FROM ' . TB_PREFIX . 'global_config');
    if (!$sth) {
        return $defaults;
    }
    $rows = $sth->fetchAll(PDO::FETCH_KEY_PAIR);
    if (!is_array($rows)) {
        $rows = [];
    }
    foreach ($defaults as $k => $v) {
        if (!array_key_exists($k, $rows)) {
            $rows[$k] = $v;
        }
    }
    return array_merge($defaults, $rows);
}

/**
 * @param array<string, mixed> $post
 */
function saveGlobalAppSettingsFromPost(array $post): bool
{
    $keys = array_keys(si_global_config_seed_values());
    foreach ($keys as $k) {
        if (!array_key_exists($k, $post)) {
            continue;
        }
        $val = is_string($post[$k]) ? $post[$k] : (string) $post[$k];
        $val = str_replace(["\0"], '', $val);
        if (strlen($val) > 65535) {
            $val = substr($val, 0, 65535);
        }
        si_global_config_upsert($k, $val);
    }
    return true;
}
