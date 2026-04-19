<?php

$menu = false;

require_once './include/auth/password.php';

$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
$errorMessage = '';

$portalDomainId = isset($_GET['domain_id']) ? (int) $_GET['domain_id'] : 0;
if ($portalDomainId < 1 && isset($_POST['portal_domain_id'])) {
    $portalDomainId = (int) $_POST['portal_domain_id'];
}

$domainName = '';
$resolvePortalDomain = static function (int $id): array {
    if ($id < 1) {
        return [0, ''];
    }
    $dsth = dbQuery(
        'SELECT name FROM ' . TB_PREFIX . 'user_domain WHERE id = :id LIMIT 1',
        ':id',
        $id
    );
    $drow = $dsth ? $dsth->fetch(PDO::FETCH_ASSOC) : false;
    if ($drow) {
        return [$id, (string) $drow['name']];
    }
    return [0, ''];
};

if ($portalDomainId > 0) {
    list($portalDomainId, $domainName) = $resolvePortalDomain($portalDomainId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'customer_login') {
    $token        = (string) ($_POST['csrfprotectionbysr'] ?? '');
    $sessionToken = (string) ($_SESSION['login_csrf_token'] ?? '');

    if ($sessionToken === '' || !hash_equals($sessionToken, $token)) {
        $errorMessage = $LANG['invalid_csrf'] ?? 'Invalid request. Please try again.';
    } else {
        list($portalDomainId, $domainName) = $resolvePortalDomain($portalDomainId);
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
