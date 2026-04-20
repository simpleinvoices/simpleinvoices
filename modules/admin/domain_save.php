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

    require_once __DIR__ . '/../../include/auth/create_domain_administrator.php';

    $name           = (string) ($_POST['name'] ?? '');
    $admin_email    = (string) ($_POST['admin_email'] ?? '');
    $admin_name     = (string) ($_POST['admin_name'] ?? '');
    $admin_password = (string) ($_POST['admin_password'] ?? '');
    $registration_language = isset($_POST['registration_language'])
        ? trim((string) $_POST['registration_language'])
        : '';

    $result = auth_try_create_domain_with_administrator(
        $name,
        $admin_email,
        $admin_name,
        $admin_password,
        $registration_language !== '' ? $registration_language : null
    );
    if ($result['success']) {
        $saved = true;
        $newDomainId = (int) ($result['domain_id'] ?? 0);
        if ($newDomainId > 1 && !domainHasEssentialBootstrapData($newDomainId)) {
            require_once __DIR__ . '/../../include/install_workspace_bootstrap.php';
            $bootstrapLang = $registration_language !== ''
                ? si_normalize_registration_language($registration_language)
                : null;
            install_bootstrap_new_domain_essentials($newDomainId, $bootstrapLang);
        }
    } else {
        $error = $result['error'];
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
    // index.php renders the HTML header before loading this module, so Location headers
    // often cannot be sent; exiting here would leave a blank page. Fall through to
    // assign Blade vars and render domain_save.blade.php when output has already started.
    if (!headers_sent()) {
        header('Location: index.php?module=admin&view=domains&domain_saved=' . rawurlencode((string) $op));
        exit();
    }
}

$bladeView->assign('saved', $saved);
$bladeView->assign('saveError', $error);
$bladeView->assign('savedOp', $saved ? (string) $op : '');
$bladeView->assign('pageActive', 'admin');
