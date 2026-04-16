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

    $name = trim((string) ($_POST['name'] ?? ''));
    if ($name === '') {
        $error = 'Domain name is required.';
    } else {
        $sth = dbQuery(
            "INSERT INTO " . TB_PREFIX . "user_domain (name) VALUES (:name)",
            ':name', $name
        );
        $saved = (bool) $sth;
        if (!$saved) {
            $error = 'Could not create domain — the name may already be taken.';
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

$bladeView->assign('saved', $saved);
$bladeView->assign('saveError', $error);
$bladeView->assign('pageActive', 'admin');
