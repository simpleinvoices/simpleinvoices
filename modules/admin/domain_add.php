<?php
/*
 * Script: admin/domain_add.php
 *   Add a new domain — administrator role only
 *
 * License: GPL v3 or above
 */

checkLogin();

if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

if (!empty($_POST['name'])) {
    include './modules/admin/domain_save.php';
}

$bladeView->assign('domainSaveCsrfToken', siNonce('domain_save'));
$bladeView->assign('pageActive', 'admin');
