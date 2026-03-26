<?php
/*
* Script: login.php
* 	Login page – secure authentication (CSRF, bcrypt, session regeneration).
*
* License:
*	 GPL v3 or above
*/

$menu = false;
if (!defined('BROWSE')) {
	define('BROWSE', 'browse');
}

Zend_Session::start();

require_once __DIR__ . '/../../include/auth/password.php';

$errorMessage = '';

// Only process login on POST with action=login
$isLoginAttempt = isset($_POST['action']) && $_POST['action'] === 'login';

if ($isLoginAttempt) {
	// CSRF protection (Laravel-style token validation before any state change)
	$csrfToken = $_POST['csrfprotectionbysr'] ?? '';
	if (!verifySiNonce($csrfToken, 'auth_login', 0)) {
		$errorMessage = 'Invalid request. Please try again.';
	} elseif (empty($_POST['user']) || empty($_POST['pass'])) {
		$errorMessage = 'Email and password are required.';
	} else {
		$userEmail = trim((string) $_POST['user']);
		$password = (string) $_POST['pass'];

		$user = auth_authenticate_user($userEmail, $password);

		if ($user !== false) {
			// Regenerate session ID to prevent session fixation (modern best practice)
			Zend_Session::regenerateId();

			$authNamespace = new Zend_Session_Namespace('Zend_Auth');
			$authNamespace->setExpirationSeconds(60 * 60); // 1 hour
			foreach ($user as $key => $value) {
				$authNamespace->$key = $value;
			}

			if (!empty($authNamespace->role_name) && $authNamespace->role_name === 'customer' && !empty($authNamespace->user_id) && (int) $authNamespace->user_id > 0) {
				header('Location: index.php?module=customers&view=details&action=view&id=' . (int) $authNamespace->user_id);
			} else {
				header('Location: .');
			}
			exit;
		}

		// Generic message to avoid user enumeration (Laravel-style)
		$errorMessage = 'Invalid credentials.';
	}
}

// CSRF token for login form (no logged-in user yet, use fixed id 0)
$smarty->assign('loginCsrfToken', siNonce('auth_login', 0));
$smarty->assign('errorMessage', $errorMessage);
