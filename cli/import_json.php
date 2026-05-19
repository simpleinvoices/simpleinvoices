<?php
/**
 * JSON backup import (CLI) - same restore as Options → Database backup → JSON import,
 * without web server / PHP-FPM timeouts.
 *
 * Usage:
 *   php cli/import_json.php /path/to/simple_invoices_data.json
 *
 * Memory: the JSON string and decoded arrays both live in RAM (often several × file size).
 * Override when 8G is not enough (use -1 for PHP “unlimited”, subject to Docker/host RAM):
 *   SI_IMPORT_MEMORY_LIMIT=-1 php cli/import_json.php ./dump.json
 *   docker compose exec -e SI_IMPORT_MEMORY_LIMIT=-1 simpleinvoices php /var/www/html/cli/import_json.php /tmp/import.json
 *
 * Default CLI limit if unset: 8192M.
 */

chdir(dirname(__DIR__));

require_once './vendor/autoload.php';

define('BROWSE', true);
include_once './config/define.php';

include_once './include/class/ConfigLoader.php';

$config = is_file('./config/custom.config.php')
    ? ConfigLoader::load('./config/custom.config.php', '')
    : ConfigLoader::load('./config/config.php', '');

if (!isset($config->extension) || !$config->extension) {
    $config->extension = ConfigData::fromArray([]);
}

require_once './include/backup.lib.php';

if (($argc ?? 0) < 2) {
    fwrite(STDERR, "Usage: php cli/import_json.php /path/to/export.json\n");
    exit(1);
}

$path = $argv[1];
if (!is_readable($path)) {
    fwrite(STDERR, "Cannot read file: {$path}\n");
    exit(1);
}

set_time_limit(0);
$mem = getenv('SI_IMPORT_MEMORY_LIMIT');
if ($mem !== false && $mem !== '') {
    ini_set('memory_limit', $mem);
} else {
    ini_set('memory_limit', '8192M');
}

$json_string = file_get_contents($path);
if ($json_string === false) {
    fwrite(STDERR, "Failed to read file contents.\n");
    exit(1);
}

try {
    $oBack = new backup_db();
    $oBack->restore_from_json($json_string);
} catch (Throwable $e) {
    fwrite(STDERR, 'Import failed: ' . $e->getMessage() . "\n");
    exit(1);
}

fwrite(STDOUT, "Import completed successfully.\n");
exit(0);
