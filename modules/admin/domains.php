<?php
/*
 * Script: admin/domains.php
 *   Domain list — administrator role only
 *
 * License: GPL v3 or above
 */

checkLogin();

if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$sth = dbQuery(
    "SELECT d.id, d.name,
            COUNT(u.id) AS user_count
     FROM " . TB_PREFIX . "user_domain d
     LEFT JOIN " . TB_PREFIX . "user u ON u.domain_id = d.id
     GROUP BY d.id, d.name
     ORDER BY d.id"
);
$domains = $sth->fetchAll(PDO::FETCH_ASSOC);

$domainSavedOp = (string) ($_GET['domain_saved'] ?? '');
if (!in_array($domainSavedOp, ['insert_domain', 'update_domain'], true)) {
    $domainSavedOp = '';
}

$bladeView->assign('domains', $domains);
$bladeView->assign('domainSavedOp', $domainSavedOp);
$bladeView->assign('pageActive', 'admin');
