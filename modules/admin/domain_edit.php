<?php
/*
 * Script: admin/domain_edit.php
 *   Edit an existing domain — administrator role only
 *
 * License: GPL v3 or above
 */

checkLogin();

if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) {
    header('Location: index.php?module=admin&view=domains');
    exit();
}

if (!empty($_POST['name'])) {
    include './modules/admin/domain_save.php';
} else {
    $sth = dbQuery(
        "SELECT id, name FROM " . TB_PREFIX . "user_domain WHERE id = :id",
        ':id', $id
    );
    $domain = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$domain) {
        header('Location: index.php?module=admin&view=domains');
        exit();
    }
    $bladeView->assign('domain', $domain);
}

$bladeView->assign('domainSaveCsrfToken', siNonce('domain_save'));
$bladeView->assign('pageActive', 'admin');
