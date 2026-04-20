<?php
/*
 * Script: domain_admin/user_save.php
 *   Insert / update a customer or biller login account
 *   Included by user_add.php and user_edit.php
 *
 * License: GPL v3 or above
 */

checkLogin();

$allowed_roles = ['administrator', 'domain_administrator'];
if (!in_array($auth_session->role_name ?? '', $allowed_roles, true)) {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

require_once __DIR__ . '/../../include/auth/password.php';
require_once __DIR__ . '/../../include/user_ui_language.php';

$domain_id = (int) $auth_session->domain_id;
$op        = !empty($_POST['op']) ? preg_replace('/[^a-z_]/', '', (string) $_POST['op']) : null;
$saved     = false;
$saveError = null;

// Role IDs for customer and biller (fixed — only these two are manageable here)
$allowed_role_map = ['customer' => 5, 'biller' => 6];

if ($op === 'insert_domain_user') {
    requireCSRFProtection('domain_user_save');

    $email        = trim((string) ($_POST['email'] ?? ''));
    $name         = trim((string) ($_POST['name'] ?? ''));
    $password     = (string) ($_POST['password_field'] ?? '');
    $role_key     = in_array($_POST['role_key'] ?? '', ['customer', 'biller'], true)
                    ? $_POST['role_key'] : null;
    $linked_id    = (int) ($_POST['linked_id'] ?? 0);
    $enabled      = (int) ($_POST['enabled'] ?? 1);

    if (!$email) {
        $saveError = 'Email is required.';
    } elseif (!$role_key) {
        $saveError = 'Role must be customer or biller.';
    } elseif ($linked_id < 1) {
        $saveError = 'Please select a customer or biller to link.';
    } elseif (!$password) {
        $saveError = 'Password is required for new accounts.';
    } elseif (strlen($password) < 4) {
        $saveError = 'Password must be at least 4 characters.';
    } else {
        // Verify the linked entity belongs to this domain
        $table  = $role_key === 'customer' ? TB_PREFIX . 'customers' : TB_PREFIX . 'biller';
        $check  = dbQuery("SELECT id FROM {$table} WHERE id = :id AND domain_id = :domain_id",
                          ':id', $linked_id, ':domain_id', $domain_id);
        if (!$check->fetch()) {
            $saveError = 'Selected ' . $role_key . ' does not exist in your domain.';
        } else {
            $role_id      = $allowed_role_map[$role_key];
            $passwordHash = auth_hash_password($password);
            list($authStaffEmail, $authCustomerKey) = auth_identity_columns_for_role($role_id, $domain_id, $email);
            $prefCol = checkFieldExists(TB_PREFIX . 'user', 'preferred_language');
            $prefVal = $prefCol ? si_user_preferred_language_from_post() : null;
            if ($prefCol) {
                $sth = dbQuery(
                    "INSERT INTO " . TB_PREFIX . "user
                     (email, name, password, role_id, domain_id, enabled, user_id, auth_staff_email, auth_customer_key, preferred_language)
                     VALUES (:email, :name, :password, :role_id, :domain_id, :enabled, :user_id, :auth_staff_email, :auth_customer_key, :pref_lang)",
                    ':email',     $email,
                    ':name',      $name,
                    ':password',  $passwordHash,
                    ':role_id',   $role_id,
                    ':domain_id', $domain_id,
                    ':enabled',   $enabled,
                    ':user_id',   $linked_id,
                    ':auth_staff_email', $authStaffEmail,
                    ':auth_customer_key', $authCustomerKey,
                    ':pref_lang', $prefVal
                );
            } else {
                $sth = dbQuery(
                    "INSERT INTO " . TB_PREFIX . "user
                     (email, name, password, role_id, domain_id, enabled, user_id, auth_staff_email, auth_customer_key)
                     VALUES (:email, :name, :password, :role_id, :domain_id, :enabled, :user_id, :auth_staff_email, :auth_customer_key)",
                    ':email',     $email,
                    ':name',      $name,
                    ':password',  $passwordHash,
                    ':role_id',   $role_id,
                    ':domain_id', $domain_id,
                    ':enabled',   $enabled,
                    ':user_id',   $linked_id,
                    ':auth_staff_email', $authStaffEmail,
                    ':auth_customer_key', $authCustomerKey
                );
            }
            if ($sth) {
                $saved = true;
            } else {
                $saveError = 'Could not create account — the email address may already be in use.';
            }
        }
    }
}

if ($op === 'update_domain_user') {
    requireCSRFProtection('domain_user_save');

    $id           = (int) ($_POST['id'] ?? 0);
    $email        = trim((string) ($_POST['email'] ?? ''));
    $name         = trim((string) ($_POST['name'] ?? ''));
    $password     = trim((string) ($_POST['password_field'] ?? ''));
    $role_key     = in_array($_POST['role_key'] ?? '', ['customer', 'biller'], true)
                    ? $_POST['role_key'] : null;
    $linked_id    = (int) ($_POST['linked_id'] ?? 0);
    $enabled      = (int) ($_POST['enabled'] ?? 1);

    if ($id < 1 || !$email) {
        $saveError = 'Invalid request.';
    } elseif (!$role_key) {
        $saveError = 'Role must be customer or biller.';
    } elseif ($linked_id < 1) {
        $saveError = 'Please select a customer or biller to link.';
    } else {
        // Confirm the user belongs to this domain and is customer/biller
        $sth = dbQuery(
            "SELECT u.id FROM " . TB_PREFIX . "user u
             JOIN " . TB_PREFIX . "user_role r ON u.role_id = r.id
             WHERE u.id = :id AND u.domain_id = :domain_id AND r.name IN ('customer','biller')",
            ':id', $id, ':domain_id', $domain_id
        );
        if (!$sth->fetch()) {
            $saveError = 'User not found in your domain.';
        } else {
            // Verify linked entity is in this domain
            $table = $role_key === 'customer' ? TB_PREFIX . 'customers' : TB_PREFIX . 'biller';
            $check = dbQuery("SELECT id FROM {$table} WHERE id = :id AND domain_id = :domain_id",
                             ':id', $linked_id, ':domain_id', $domain_id);
            if (!$check->fetch()) {
                $saveError = 'Selected ' . $role_key . ' does not exist in your domain.';
            } else {
                $role_id = $allowed_role_map[$role_key];
                $sth     = null;
                list($authStaffEmail, $authCustomerKey) = auth_identity_columns_for_role($role_id, $domain_id, $email);
                $prefCol = checkFieldExists(TB_PREFIX . 'user', 'preferred_language');
                $prefVal = $prefCol ? si_user_preferred_language_from_post() : null;

                if ($password !== '' && strlen($password) < 4) {
                    $saveError = 'Password must be at least 4 characters.';
                } elseif ($password !== '') {
                    $passwordHash = auth_hash_password($password);
                    if ($prefCol) {
                        $sth = dbQuery(
                            "UPDATE " . TB_PREFIX . "user
                             SET email=:email, name=:name, password=:password,
                                 role_id=:role_id, enabled=:enabled, user_id=:user_id,
                                 auth_staff_email=:auth_staff_email, auth_customer_key=:auth_customer_key,
                                 preferred_language=:pref_lang
                             WHERE id=:id AND domain_id=:domain_id",
                            ':email',     $email,
                            ':name',      $name,
                            ':password',  $passwordHash,
                            ':role_id',   $role_id,
                            ':enabled',   $enabled,
                            ':user_id',   $linked_id,
                            ':auth_staff_email', $authStaffEmail,
                            ':auth_customer_key', $authCustomerKey,
                            ':pref_lang', $prefVal,
                            ':id',        $id,
                            ':domain_id', $domain_id
                        );
                    } else {
                        $sth = dbQuery(
                            "UPDATE " . TB_PREFIX . "user
                             SET email=:email, name=:name, password=:password,
                                 role_id=:role_id, enabled=:enabled, user_id=:user_id,
                                 auth_staff_email=:auth_staff_email, auth_customer_key=:auth_customer_key
                             WHERE id=:id AND domain_id=:domain_id",
                            ':email',     $email,
                            ':name',      $name,
                            ':password',  $passwordHash,
                            ':role_id',   $role_id,
                            ':enabled',   $enabled,
                            ':user_id',   $linked_id,
                            ':auth_staff_email', $authStaffEmail,
                            ':auth_customer_key', $authCustomerKey,
                            ':id',        $id,
                            ':domain_id', $domain_id
                        );
                    }
                } else {
                    if ($prefCol) {
                        $sth = dbQuery(
                            "UPDATE " . TB_PREFIX . "user
                             SET email=:email, name=:name,
                                 role_id=:role_id, enabled=:enabled, user_id=:user_id,
                                 auth_staff_email=:auth_staff_email, auth_customer_key=:auth_customer_key,
                                 preferred_language=:pref_lang
                             WHERE id=:id AND domain_id=:domain_id",
                            ':email',     $email,
                            ':name',      $name,
                            ':role_id',   $role_id,
                            ':enabled',   $enabled,
                            ':user_id',   $linked_id,
                            ':auth_staff_email', $authStaffEmail,
                            ':auth_customer_key', $authCustomerKey,
                            ':pref_lang', $prefVal,
                            ':id',        $id,
                            ':domain_id', $domain_id
                        );
                    } else {
                        $sth = dbQuery(
                            "UPDATE " . TB_PREFIX . "user
                             SET email=:email, name=:name,
                                 role_id=:role_id, enabled=:enabled, user_id=:user_id,
                                 auth_staff_email=:auth_staff_email, auth_customer_key=:auth_customer_key
                             WHERE id=:id AND domain_id=:domain_id",
                            ':email',     $email,
                            ':name',      $name,
                            ':role_id',   $role_id,
                            ':enabled',   $enabled,
                            ':user_id',   $linked_id,
                            ':auth_staff_email', $authStaffEmail,
                            ':auth_customer_key', $authCustomerKey,
                            ':id',        $id,
                            ':domain_id', $domain_id
                        );
                    }
                }
                if ($sth !== null) {
                    $saved = $sth ? true : false;
                    if (!$saved) {
                        $saveError = 'Could not update account — the email may already be in use.';
                    }
                }
            }
        }
    }
}

if ($saved && $op === 'update_domain_user' && checkFieldExists(TB_PREFIX . 'user', 'preferred_language')) {
    $editedId = (int) ($_POST['id'] ?? 0);
    if ($editedId === (int) ($auth_session->id ?? 0)) {
        $pv = si_user_preferred_language_from_post();
        $auth_session->ui_language = $pv === null ? '' : $pv;
    }
}

if ($saved && ($op === 'insert_domain_user' || $op === 'update_domain_user')) {
    header('Location: index.php?module=domain_admin&view=users&domain_user_saved=' . rawurlencode((string) $op));
    exit();
}

$bladeView->assign('saved',     $saved);
$bladeView->assign('saveError', $saveError);
$bladeView->assign('pageActive', 'domain_admin');
