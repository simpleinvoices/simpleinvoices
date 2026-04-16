<?php
/*
 * Script: domain_admin/users.php
 *   List customer and biller login accounts in this domain
 *
 * License: GPL v3 or above
 */

checkLogin();

$allowed_roles = ['administrator', 'domain_administrator'];
if (!in_array($auth_session->role_name ?? '', $allowed_roles, true)) {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$domain_id = (int) $auth_session->domain_id;

$sth = dbQuery(
    "SELECT u.id, u.email, u.name, u.enabled, u.user_id,
            r.name AS role_name,
            c.name AS customer_name,
            b.name  AS biller_name
     FROM " . TB_PREFIX . "user u
     JOIN " . TB_PREFIX . "user_role r ON u.role_id = r.id
     LEFT JOIN " . TB_PREFIX . "customers c
          ON r.name = 'customer' AND c.domain_id = u.domain_id AND c.id = u.user_id
     LEFT JOIN " . TB_PREFIX . "biller b
          ON r.name = 'biller' AND b.domain_id = u.domain_id AND b.id = u.user_id
     WHERE u.domain_id = :domain_id
       AND r.name IN ('customer', 'biller')
     ORDER BY r.name, u.name",
    ':domain_id', $domain_id
);
$domainUsers = $sth->fetchAll(PDO::FETCH_ASSOC);

$bladeView->assign('domainUsers', $domainUsers);
$bladeView->assign('pageActive', 'domain_admin');
