<?php
/*
 * Script: domain_admin/index.php
 *   Domain admin dashboard — domain_administrator and administrator roles only
 *
 * License: GPL v3 or above
 */

checkLogin();

$allowed_roles = ['administrator', 'domain_administrator'];
if (!in_array($auth_session->role_name ?? '', $allowed_roles, true)) {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$domain_id = (int) $auth_session->domain_id;

// Count customer users in this domain
$sth = dbQuery(
    "SELECT COUNT(*) AS cnt
     FROM " . TB_PREFIX . "user u
     JOIN " . TB_PREFIX . "user_role r ON u.role_id = r.id
     WHERE u.domain_id = :domain_id AND r.name = 'customer'",
    ':domain_id', $domain_id
);
$customerUserCount = (int) ($sth->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

// Count biller users in this domain
$sth = dbQuery(
    "SELECT COUNT(*) AS cnt
     FROM " . TB_PREFIX . "user u
     JOIN " . TB_PREFIX . "user_role r ON u.role_id = r.id
     WHERE u.domain_id = :domain_id AND r.name = 'biller'",
    ':domain_id', $domain_id
);
$billerUserCount = (int) ($sth->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

// Count unlinked customers (no login account yet)
$sth = dbQuery(
    "SELECT COUNT(*) AS cnt
     FROM " . TB_PREFIX . "customers c
     WHERE c.domain_id = :domain_id AND c.enabled = 1
       AND NOT EXISTS (
           SELECT 1 FROM " . TB_PREFIX . "user u
           JOIN " . TB_PREFIX . "user_role r ON u.role_id = r.id
           WHERE r.name = 'customer' AND u.user_id = c.id AND u.domain_id = :domain_id2
       )",
    ':domain_id', $domain_id,
    ':domain_id2', $domain_id
);
$unlinkedCustomers = (int) ($sth->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

// Count unlinked billers
$sth = dbQuery(
    "SELECT COUNT(*) AS cnt
     FROM " . TB_PREFIX . "biller b
     WHERE b.domain_id = :domain_id AND b.enabled = 1
       AND NOT EXISTS (
           SELECT 1 FROM " . TB_PREFIX . "user u
           JOIN " . TB_PREFIX . "user_role r ON u.role_id = r.id
           WHERE r.name = 'biller' AND u.user_id = b.id AND u.domain_id = :domain_id2
       )",
    ':domain_id', $domain_id,
    ':domain_id2', $domain_id
);
$unlinkedBillers = (int) ($sth->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

$siBase            = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
$customerPortalUrl = $siBase . '/index.php?module=auth&view=customer_login&domain_id=' . $domain_id;

$bladeView->assign('customerUserCount', $customerUserCount);
$bladeView->assign('billerUserCount', $billerUserCount);
$bladeView->assign('unlinkedCustomers', $unlinkedCustomers);
$bladeView->assign('unlinkedBillers', $unlinkedBillers);
$bladeView->assign('customerPortalUrl', $customerPortalUrl);
$bladeView->assign('pageActive', 'domain_admin');
