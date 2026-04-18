<?php
/*
 * Script: admin/domain_save.php
 *   Insert / update / delete a domain — administrator role only
 *
 * License: GPL v3 or above
 */

checkLogin();

if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

$op    = !empty($_POST['op']) ? preg_replace('/[^a-z_]/', '', (string) $_POST['op']) : null;
$saved = false;
$error = null;

if ($op === 'insert_domain') {
    requireCSRFProtection('domain_save');

    require_once __DIR__ . '/../../include/auth/password.php';

    $name           = trim((string) ($_POST['name'] ?? ''));
    $admin_email    = trim((string) ($_POST['admin_email'] ?? ''));
    $admin_name     = trim((string) ($_POST['admin_name'] ?? ''));
    $admin_password = (string) ($_POST['admin_password'] ?? '');

    if ($name === '') {
        $error = 'Domain name is required.';
    } elseif ($admin_email === '') {
        $error = 'Domain administrator email is required.';
    } elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address for the domain administrator.';
    } elseif ($admin_password === '') {
        $error = 'Domain administrator password is required.';
    } elseif (strlen($admin_password) < 4) {
        $error = 'Domain administrator password must be at least 4 characters.';
    } else {
        $role_sth = dbQuery(
            "SELECT id FROM " . TB_PREFIX . "user_role WHERE name = 'domain_administrator' LIMIT 1"
        );
        $role_row = $role_sth ? $role_sth->fetch(PDO::FETCH_ASSOC) : false;
        $role_id  = (int) ($role_row['id'] ?? 0);
        if ($role_id < 1) {
            $error = 'Could not create domain — the domain_administrator role is missing from the database.';
        } else {
            global $dbh;
            $password_hash = auth_hash_password($admin_password);
            $tx_ok          = false;
            try {
                $dbh->beginTransaction();

                dbQuery(
                    "INSERT INTO " . TB_PREFIX . "user_domain (name) VALUES (:name)",
                    ':name', $name
                );
                $new_domain_id = (int) lastInsertId();
                $domain_row     = $new_domain_id > 0
                    ? dbQuery(
                        "SELECT id FROM " . TB_PREFIX . "user_domain WHERE id = :id AND name = :name LIMIT 1",
                        ':id', $new_domain_id,
                        ':name', $name
                    )->fetch(PDO::FETCH_ASSOC)
                    : false;
                if (!$domain_row) {
                    throw new RuntimeException('domain_insert');
                }

                dbQuery(
                    "INSERT INTO " . TB_PREFIX . "user
                     (email, name, password, role_id, domain_id, enabled, user_id)
                     VALUES (:email, :name, :password, :role_id, :domain_id, 1, 0)",
                    ':email',     $admin_email,
                    ':name',      $admin_name !== '' ? $admin_name : null,
                    ':password',  $password_hash,
                    ':role_id',   $role_id,
                    ':domain_id', $new_domain_id
                );

                $user_check = dbQuery(
                    "SELECT id FROM " . TB_PREFIX . "user
                     WHERE domain_id = :domain_id AND email = :email LIMIT 1",
                    ':domain_id', $new_domain_id,
                    ':email',     $admin_email
                );
                if (!$user_check || !$user_check->fetch(PDO::FETCH_ASSOC)) {
                    throw new RuntimeException('user_insert');
                }

                $dbh->commit();
                $tx_ok = true;
            } catch (Throwable $e) {
                if ($dbh->inTransaction()) {
                    $dbh->rollBack();
                }
                $tx_ok = false;
            }

            if ($tx_ok) {
                $saved = true;
            } elseif ($error === null) {
                $error = 'Could not create the domain and administrator account — the domain name or email may already be in use.';
            }
        }
    }
}

if ($op === 'update_domain') {
    requireCSRFProtection('domain_save');

    $id   = (int) ($_POST['id'] ?? 0);
    $name = trim((string) ($_POST['name'] ?? ''));

    if ($id < 1 || $name === '') {
        $error = 'Invalid domain ID or empty name.';
    } else {
        $sth = dbQuery(
            "UPDATE " . TB_PREFIX . "user_domain SET name = :name WHERE id = :id",
            ':name', $name,
            ':id',   $id
        );
        $saved = (bool) $sth;
        if (!$saved) {
            $error = 'Could not update domain — the name may already be taken.';
        }
    }
}

if ($op === 'delete_domain') {
    requireCSRFProtection('domain_save');

    $id = (int) ($_POST['id'] ?? 0);

    if ($id === 1) {
        $error = 'The default domain (ID 1) cannot be deleted.';
    } elseif ($id < 1) {
        $error = 'Invalid domain ID.';
    } else {
        // Check if any users belong to this domain
        $check = dbQuery(
            "SELECT COUNT(*) AS cnt FROM " . TB_PREFIX . "user WHERE domain_id = :id",
            ':id', $id
        );
        $cnt = (int) ($check->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);

        if ($cnt > 0) {
            $error = "Cannot delete domain — it still has {$cnt} user(s) assigned to it.";
        } else {
            $sth = dbQuery(
                "DELETE FROM " . TB_PREFIX . "user_domain WHERE id = :id",
                ':id', $id
            );
            $saved = (bool) $sth;
        }
    }
}

if ($saved && ($op === 'insert_domain' || $op === 'update_domain')) {
    header('Location: index.php?module=admin&view=domains&domain_saved=' . rawurlencode((string) $op));
    exit();
}

$bladeView->assign('saved', $saved);
$bladeView->assign('saveError', $error);
$bladeView->assign('savedOp', $saved ? (string) $op : '');
$bladeView->assign('pageActive', 'admin');
