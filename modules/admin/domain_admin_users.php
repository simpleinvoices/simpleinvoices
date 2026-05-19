<?php
/*
 * Script: admin/domain_admin_users.php
 *   List all domain_administrator users across all domains
 *
 * License: GPL v3 or above
 */

checkLogin();

if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$sth = dbQuery(
    "SELECT u.id, u.email, u.name, u.enabled,
            d.name AS domain_name, d.id AS domain_id
     FROM " . TB_PREFIX . "user u
     JOIN " . TB_PREFIX . "user_role r  ON u.role_id = r.id
     JOIN " . TB_PREFIX . "user_domain d ON u.domain_id = d.id
     WHERE r.name = 'domain_administrator'
     ORDER BY d.id, u.name"
);
$domainAdminUsers = $sth->fetchAll(PDO::FETCH_ASSOC);

$userSavedOp = (string) ($_GET['user_saved'] ?? '');
if (!in_array($userSavedOp, ['insert_user', 'edit_user'], true)) {
    $userSavedOp = '';
}

$bladeView->assign('domainAdminUsers', $domainAdminUsers);
$bladeView->assign('userSavedOp', $userSavedOp);
$bladeView->assign('pageActive', 'admin');
