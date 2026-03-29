<?php

$menu = false;

require_once('./include/auth/password.php');

$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $token = (string) ($_POST['csrfprotectionbysr'] ?? '');
    $sessionToken = (string) ($_SESSION['login_csrf_token'] ?? '');

    if ($sessionToken === '' || !hash_equals($sessionToken, $token)) {
        $errorMessage = $LANG['invalid_csrf'] ?? 'Invalid request. Please try again.';
    } else {
        $email    = trim((string) ($_POST['user'] ?? ''));
        $password = (string) ($_POST['pass'] ?? '');
        $user     = auth_authenticate_user($email, $password);

        if ($user) {
            session_regenerate_id(true);

            $auth_session->id        = (string) $user['id'];
            $auth_session->email     = (string) $user['email'];
            $auth_session->role_name = (string) ($user['role_name'] ?? '');
            $auth_session->domain_id = (string) ($user['domain_id'] ?? '1');
            $auth_session->user_id   = (string) ($user['user_id'] ?? '0');

            unset($_SESSION['login_csrf_token']);

            if (($user['role_name'] ?? '') === 'customer' && (int) ($user['user_id'] ?? 0) > 0) {
                header('Location: ' . $siBase . '/index.php?module=customers&view=details&action=view&id=' . (int) $user['user_id']);
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

$smarty->assign('loginCsrfToken', $loginCsrfToken);
$smarty->assign('errorMessage', $errorMessage);
