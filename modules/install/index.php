<?php

global $db_server;

$menu = false;
$redirect_after_install = null;
$install_error = false;

/**
 * Execute a multi-statement SQL file by splitting on semicolons.
 *
 * PDO::prepare() only handles a single statement, so passing an entire
 * structure.sql file in one call fails on PostgreSQL and SQLite (and is
 * unreliable on MySQL).  This function strips -- comments, splits on ';',
 * and calls dbQuery() for each individual statement.
 *
 * Returns true if all statements succeed (no exception thrown).
 */
function install_execute_sql_file($sql_content) {
    // Strip -- line comments and blank lines so we don't submit empty statements
    $lines = explode("\n", $sql_content);
    $cleaned = [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
            continue;
        }
        $cleaned[] = $trimmed;
    }

    $statements = array_filter(
        array_map('trim', explode(';', implode("\n", $cleaned))),
        'strlen'
    );

    foreach ($statements as $stmt) {
        dbQuery($stmt);
    }
    return true;
}

if (isset($_POST['op']) && $_POST['op'] === 'install_database') {
    $install_successful = true;

    if (checkTableExists() == false) {
        $import = new import();

        // Select the schema file that matches the configured database type.
        if ($db_server == 'pgsql') {
            $import->file = "./databases/pgsql/structure.sql";
        } elseif ($db_server == 'sqlite') {
            $import->file = "./databases/sqlite/structure.sql";
        } else {
            $import->file = "./databases/mysql/structure.sql";
        }

        $import->pattern_find    = ['si_', 'DOMAIN-ID', 'LOCALE', 'LANGUAGE'];
        $import->pattern_replace = [TB_PREFIX, '1', 'en_GB', 'en_GB'];

        try {
            $install_successful = install_execute_sql_file($import->collate());
        } catch (Exception $e) {
            $install_successful = false;
        }
    }

    if ($install_successful && checkTableExists(TB_PREFIX."customers") == true) {
        $need_essential = !isset($install_data_exists) || $install_data_exists == false;
        if ($need_essential) {
            $importjson = new importjson();
            $importjson->file = "./databases/json/essential_data.json";
            $importjson->pattern_find    = ['si_', 'DOMAIN-ID', 'LOCALE', 'LANGUAGE'];
            $importjson->pattern_replace = [TB_PREFIX, '1', 'en_GB', 'en_GB'];
            try {
                $install_successful = install_execute_sql_file($importjson->collate());
            } catch (Exception $e) {
                $install_successful = false;
            }
        }

        if ($install_successful) {
            // The schema imported from structure.sql already reflects the latest
            // state.  Mark every patch as done so the patch-runner page never
            // appears on first login (patches are only for upgrading older installs).
            include_once('./include/sql_patches.php');
            install_mark_all_patches_done();
        }
    }

    if ($install_successful && checkTableExists(TB_PREFIX."biller") == true && checkDataExists() == true) {
        $redirect_after_install = 'index.php?module=index&view=index';
    } elseif (isset($_POST['op'])) {
        $install_error = true;
    }
}

$bladeView->assign('redirect_after_install', $redirect_after_install);
$bladeView->assign('install_error', $install_error);
?>
