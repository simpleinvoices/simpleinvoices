<?php
/*
 * Script: domain_admin/user_add.php
 *   Create a customer or biller login account linked to an existing entity
 *
 * License: GPL v3 or above
 */

checkLogin();

$allowed_roles = ['administrator', 'domain_administrator'];
if (!in_array($auth_session->role_name ?? '', $allowed_roles, true)) {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$domain_id = (int) $auth_session->domain_id;

if (!empty($_POST['email'])) {
    include './modules/domain_admin/user_save.php';
}

// Customers in this domain (enabled)
$sth = dbQuery(
    "SELECT id, name FROM " . TB_PREFIX . "customers
     WHERE domain_id = :domain_id AND enabled = 1
     ORDER BY name",
    ':domain_id', $domain_id
);
$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

// Billers in this domain (enabled)
$sth = dbQuery(
    "SELECT id, name FROM " . TB_PREFIX . "biller
     WHERE domain_id = :domain_id AND enabled = 1
     ORDER BY name",
    ':domain_id', $domain_id
);
$billers = $sth->fetchAll(PDO::FETCH_ASSOC);

$bladeView->assign('customers', $customers);
$bladeView->assign('billers', $billers);
$bladeView->assign('domainUserSaveCsrfToken', siNonce('domain_user_save'));
$bladeView->assign('pageActive', 'domain_admin');
