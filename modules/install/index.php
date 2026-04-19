<?php

global $db_server, $auth_session, $install_tables_exists, $install_data_exists;

$menu = false;
$redirect_after_install = null;
$install_error = false;

$domainIdForInstall = isset($auth_session->domain_id) ? (int) $auth_session->domain_id : 1;
$install_new_domain_bootstrap = ($install_tables_exists === true)
	&& ($install_data_exists === false)
	&& ($domainIdForInstall > 1);

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
    global $auth_session;

    $install_successful = true;
    $targetDomainId = isset($auth_session->domain_id) ? (int) $auth_session->domain_id : 1;
    $locTokens = install_essential_data_locale_tokens();
    // Full essential bundle (incl. sql_patchmanager, roles, demo user) only when the DB has never recorded a patch.
    $includeGlobalEssential = (getNumberOfDoneSQLPatches() == 0);

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
        $import->pattern_replace = [TB_PREFIX, (string) $targetDomainId, $locTokens[0], $locTokens[1]];

        try {
            $install_successful = install_execute_sql_file($import->collate());
        } catch (Exception $e) {
            $install_successful = false;
        }
    }

    if ($install_successful && checkTableExists(TB_PREFIX."customers") == true) {
        $need_essential = !isset($install_data_exists) || $install_data_exists == false;
        if ($need_essential) {
            try {
                $essentialSql = install_build_essential_data_sql($targetDomainId, $includeGlobalEssential);
                if ($essentialSql !== '') {
                    $install_successful = install_execute_sql_file($essentialSql);
                }
            } catch (Exception $e) {
                $install_successful = false;
            }
        }

        if ($install_successful && $includeGlobalEssential) {
            // The schema imported from structure.sql already reflects the latest
            // state.  Mark every patch as done so the patch-runner page never
            // appears on first login (patches are only for upgrading older installs).
            include_once('./include/sql_patches.php');
            install_mark_all_patches_done();
            invoice_denorm::rebuildDomain($targetDomainId);
        }
    }

    if ($install_successful && checkTableExists(TB_PREFIX."biller") == true && checkDataExists($targetDomainId) == true) {
        // Update admin login credentials if provided
        $adminEmail    = trim($_POST['install_admin_email'] ?? '');
        $adminPassword = $_POST['install_admin_password'] ?? '';
        if ($adminEmail !== '' && $adminPassword !== '') {
            include_once('./include/auth/password.php');
            $hashedPassword = auth_hash_password($adminPassword);
            dbQuery(
                "UPDATE " . TB_PREFIX . "user SET email = :email, password = :pw, auth_staff_email = :email2 WHERE id = 1",
                ':email', $adminEmail,
                ':pw', $hashedPassword,
                ':email2', $adminEmail
            );
        }
        $redirect_after_install = 'index.php?module=index&view=index';
    } elseif (isset($_POST['op'])) {
        $install_error = true;
    }
}

$install_welcome_step = $install_new_domain_bootstrap
	&& (($_GET['step'] ?? '') !== 'setup')
	&& empty($_POST['op']);

$bladeView->assign('redirect_after_install', $redirect_after_install);
$bladeView->assign('install_error', $install_error);
$bladeView->assign('install_new_domain_bootstrap', $install_new_domain_bootstrap);
$bladeView->assign('install_welcome_step', $install_welcome_step);
?>
