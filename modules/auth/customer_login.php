<?php

$menu = false;

require_once './include/auth/password.php';

$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
$errorMessage = '';

$portalDomainId = 0;
$domainName     = '';

$resolveByName = static function (string $name): array {
    if ($name === '') {
        return [0, ''];
    }
    $dsth = dbQuery(
        'SELECT id, name FROM ' . TB_PREFIX . 'user_domain WHERE name = :name LIMIT 1',
        ':name', $name
    );
    $drow = $dsth ? $dsth->fetch(PDO::FETCH_ASSOC) : false;
    return $drow ? [(int) $drow['id'], (string) $drow['name']] : [0, ''];
};

$resolveById = static function (int $id): array {
    if ($id < 1) {
        return [0, ''];
    }
    $dsth = dbQuery(
        'SELECT id, name FROM ' . TB_PREFIX . 'user_domain WHERE id = :id LIMIT 1',
        ':id', $id
    );
    $drow = $dsth ? $dsth->fetch(PDO::FETCH_ASSOC) : false;
    return $drow ? [(int) $drow['id'], (string) $drow['name']] : [0, ''];
};

$domainSlug = isset($_GET['domain']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_GET['domain']) : '';
if ($domainSlug !== '') {
    list($portalDomainId, $domainName) = $resolveByName($domainSlug);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'customer_login') {
    $token        = (string) ($_POST['csrfprotectionbysr'] ?? '');
    $sessionToken = (string) ($_SESSION['login_csrf_token'] ?? '');

    if ($sessionToken === '' || !hash_equals($sessionToken, $token)) {
        $errorMessage = $LANG['invalid_csrf'] ?? 'Invalid request. Please try again.';
    } else {
        list($portalDomainId, $domainName) = $resolveById((int) ($_POST['portal_domain_id'] ?? 0));
    }
    if ($errorMessage === '' && $portalDomainId < 1) {
        $errorMessage = $LANG['invalid_login'] ?? 'Invalid email or password.';
    }
    if ($errorMessage === '' && $portalDomainId > 0) {
        $email    = trim((string) ($_POST['user'] ?? ''));
        $password = (string) ($_POST['pass'] ?? '');
        $user     = auth_authenticate_customer_user($email, $password, $portalDomainId);

        if ($user) {
            session_regenerate_id(true);

            $auth_session->id        = (string) $user['id'];
            $auth_session->email     = (string) $user['email'];
            $auth_session->name      = (string) ($user['name'] ?? '');
            $auth_session->role_name = (string) ($user['role_name'] ?? '');
            $auth_session->domain_id = (string) ($user['domain_id'] ?? '1');
            $auth_session->user_id   = (string) ($user['user_id'] ?? '0');
            $auth_session->ui_language = trim((string) ($user['preferred_language'] ?? ''));

            unset($_SESSION['login_csrf_token']);

            if (($user['role_name'] ?? '') === 'customer' && (int) ($user['user_id'] ?? 0) > 0) {
                header('Location: ' . $siBase . '/index.php?module=invoices&view=manage');
            } else {
                header('Location: ' . $siBase . '/');
            }
            exit;
        }

        $errorMessage = $LANG['invalid_login'] ?? 'Invalid email or password.';
    }
}

$loginCsrfToken = bin2hex(random_bytes(16));
$_SESSION['login_csrf_token'] = $loginCsrfToken;

$bladeView->assign('loginCsrfToken', $loginCsrfToken);
$bladeView->assign('errorMessage', $errorMessage);
$bladeView->assign('portalDomainId', $portalDomainId);
$bladeView->assign('portalDomainName', $domainName);
