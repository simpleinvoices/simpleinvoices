<?php
/*
 * Script: domain_admin/user_edit.php
 *   Edit a customer or biller login account
 *
 * License: GPL v3 or above
 */

checkLogin();

$allowed_roles = ['administrator', 'domain_administrator'];
if (!in_array($auth_session->role_name ?? '', $allowed_roles, true)) {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$domain_id = (int) $auth_session->domain_id;
$id        = (int) ($_GET['id'] ?? 0);

if ($id < 1) {
    header('Location: index.php?module=domain_admin&view=users');
    exit();
}

if (!empty($_POST['email'])) {
    include './modules/domain_admin/user_save.php';
} else {
    // Load the user — must belong to this domain and be customer or biller role
    $prefSel = checkFieldExists(TB_PREFIX . 'user', 'preferred_language')
        ? ', u.preferred_language'
        : '';
    $sth = dbQuery(
        "SELECT u.id, u.email, u.name, u.enabled, u.user_id, u.role_id{$prefSel},
                r.name AS role_name
         FROM " . TB_PREFIX . "user u
         JOIN " . TB_PREFIX . "user_role r ON u.role_id = r.id
         WHERE u.id = :id AND u.domain_id = :domain_id
           AND r.name IN ('customer', 'biller')",
        ':id', $id,
        ':domain_id', $domain_id
    );
    $domainUser = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$domainUser) {
        header('Location: index.php?module=domain_admin&view=users');
        exit();
    }
    $bladeView->assign('domainUser', $domainUser);
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
$bladeView->assign('userUiLanguageList', si_get_ui_language_list_sorted());
$bladeView->assign('domainUserSaveCsrfToken', siNonce('domain_user_save'));
$bladeView->assign('pageActive', 'domain_admin');
