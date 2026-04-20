<?php

$menu = false;

require_once('./include/auth/password.php');

$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
$errorMessage   = '';
$registerError  = '';

$cfgPublicReg = $config->authentication->allow_public_domain_registration ?? false;
$publicRegistrationEnabled = (
    $cfgPublicReg === true
    || $cfgPublicReg === 1
    || (is_string($cfgPublicReg) && in_array(strtolower(trim($cfgPublicReg)), ['1', 'true', 'yes', 'on'], true))
);

$registerAllowed = $publicRegistrationEnabled
    && !empty($install_tables_exists)
    && function_exists('checkDataExists')
    && checkDataExists(1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register') {
    if (!$registerAllowed) {
        $registerError = $LANG['denied_page'] ?? 'Registration is not available.';
    } else {
        $token         = (string) ($_POST['csrfprotectionbysr'] ?? '');
        $sessionToken  = (string) ($_SESSION['register_csrf_token'] ?? '');

        if ($sessionToken === '' || !hash_equals($sessionToken, $token)) {
            $registerError = $LANG['invalid_csrf'] ?? 'Invalid request. Please try again.';
        } else {
            require_once __DIR__ . '/../../include/auth/create_domain_administrator.php';

            $domain_name     = (string) ($_POST['domain_name'] ?? '');
            $admin_email_reg = (string) ($_POST['admin_email'] ?? '');
            $admin_password  = (string) ($_POST['admin_password'] ?? '');
            $registration_language = isset($_POST['registration_language'])
                ? trim((string) $_POST['registration_language'])
                : '';

            $result = auth_try_create_domain_with_administrator(
                $domain_name,
                $admin_email_reg,
                '',
                $admin_password,
                $registration_language !== '' ? $registration_language : null
            );

            if ($result['success']) {
                $user = auth_authenticate_staff_user($admin_email_reg, $admin_password);

                if ($user) {
                    session_regenerate_id(true);

                    $auth_session->id        = (string) $user['id'];
                    $auth_session->email     = (string) $user['email'];
                    $auth_session->name      = (string) ($user['name'] ?? '');
                    $auth_session->role_name = (string) ($user['role_name'] ?? '');
                    $auth_session->domain_id = (string) ($user['domain_id'] ?? '1');
                    $auth_session->user_id   = (string) ($user['user_id'] ?? '0');
                    $auth_session->ui_language = trim((string) ($user['preferred_language'] ?? ''));

                    $newDomainId = (int) ($user['domain_id'] ?? 0);
                    if ($newDomainId > 1 && !domainHasEssentialBootstrapData($newDomainId)) {
                        require_once __DIR__ . '/../../include/install_workspace_bootstrap.php';
                        $bootstrapLang = $registration_language !== ''
                            ? si_normalize_registration_language($registration_language)
                            : null;
                        install_bootstrap_new_domain_essentials($newDomainId, $bootstrapLang);
                    }

                    unset($_SESSION['register_csrf_token'], $_SESSION['login_csrf_token']);

                    header('Location: ' . $siBase . '/index.php?module=index&view=index');
                    exit;
                }

                $registerError = 'Your organisation was created but sign-in failed. Please try logging in with the same email and password.';
            } else {
                $registerError = (string) $result['error'];
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $token = (string) ($_POST['csrfprotectionbysr'] ?? '');
    $sessionToken = (string) ($_SESSION['login_csrf_token'] ?? '');

    if ($sessionToken === '' || !hash_equals($sessionToken, $token)) {
        $errorMessage = $LANG['invalid_csrf'] ?? 'Invalid request. Please try again.';
    } else {
        $email    = trim((string) ($_POST['user'] ?? ''));
        $password = (string) ($_POST['pass'] ?? '');
        $user     = auth_authenticate_staff_user($email, $password);

        if ($user) {
            session_regenerate_id(true);

            $auth_session->id        = (string) $user['id'];
            $auth_session->email     = (string) $user['email'];
            $auth_session->name      = (string) ($user['name'] ?? '');
            $auth_session->role_name = (string) ($user['role_name'] ?? '');
            $auth_session->domain_id = (string) ($user['domain_id'] ?? '1');
            $auth_session->user_id   = (string) ($user['user_id'] ?? '0');
            $auth_session->ui_language = trim((string) ($user['preferred_language'] ?? ''));

            $loginDomainId = (int) ($user['domain_id'] ?? 0);
            if ($loginDomainId > 1 && !domainHasEssentialBootstrapData($loginDomainId)) {
                require_once __DIR__ . '/../../include/install_workspace_bootstrap.php';
                install_bootstrap_new_domain_essentials($loginDomainId);
            }

            unset($_SESSION['login_csrf_token']);

            if (($user['role_name'] ?? '') === 'customer' && (int) ($user['user_id'] ?? 0) > 0) {
                header('Location: ' . $siBase . '/index.php?module=invoices&view=manage');
            } else {
                header('Location: ' . $siBase . '/index.php?module=index&view=index');
            }
            exit;
        }

        $errorMessage = $LANG['invalid_login'] ?? 'Invalid email or password.';
    }
}

$loginCsrfToken = bin2hex(random_bytes(16));
$_SESSION['login_csrf_token'] = $loginCsrfToken;

$registerCsrfToken = bin2hex(random_bytes(16));
$_SESSION['register_csrf_token'] = $registerCsrfToken;

$registerTabActive = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register');

$registrationLangList = getLanguageList();
if (is_array($registrationLangList)) {
	usort(
		$registrationLangList,
		static function ($a, $b) {
			return strcasecmp((string) ($a->name ?? ''), (string) ($b->name ?? ''));
		}
	);
}

$bladeView->assign('registrationLanguageList', $registrationLangList ?? []);
$registrationAvailableCodes = [];
foreach ($registrationLangList ?? [] as $entry) {
	if (isset($entry->shortname) && (string) $entry->shortname !== '') {
		$registrationAvailableCodes[] = (string) $entry->shortname;
	}
}
$bladeView->assign(
	'registrationLanguageDefault',
	si_pick_ui_language_from_browser($registrationAvailableCodes, 'en_US')
);

$bladeView->assign('loginCsrfToken', $loginCsrfToken);
$bladeView->assign('registerCsrfToken', $registerCsrfToken);
$bladeView->assign('errorMessage', $errorMessage);
$bladeView->assign('registerError', $registerError);
$bladeView->assign('registerAllowed', $registerAllowed);
$bladeView->assign('registerTabActive', $registerTabActive);
