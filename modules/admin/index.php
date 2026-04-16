<?php
/*
 * Script: admin/index.php
 *   Admin dashboard — administrator role only
 *
 * License: GPL v3 or above
 */

checkLogin();

// Hard role check — ACL is the primary gate but this is defence-in-depth
if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

// Domain count
$sth = dbQuery("SELECT COUNT(*) AS cnt FROM " . TB_PREFIX . "user_domain");
$domainCount = (int) ($sth->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

// User count
$sth = dbQuery("SELECT COUNT(*) AS cnt FROM " . TB_PREFIX . "user");
$userCount = (int) ($sth->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

$bladeView->assign('domainCount', $domainCount);
$bladeView->assign('userCount', $userCount);
$bladeView->assign('pageActive', 'admin');
