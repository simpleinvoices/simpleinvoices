<?php

/*
 * Modules excluded from auth:
 *   api/paypal - PayPal IPN callbacks originate from PayPal servers
 *   api/cron   - automated scheduler, no browser session
 *
 * Web installer: when the DB has no tables yet or essential bootstrap data is
 * missing, allow the request through without a session so index.php can route
 * to module=install (otherwise login is required before routing runs).
 */
$auth_exempt_api_views = ['paypal', 'cron'];
$is_exempt_api = ($module === 'api' && in_array($view, $auth_exempt_api_views, true));

$installer_incomplete = !$install_tables_exists || !checkDataExists(1);

if (!$is_exempt_api) {
	if (!isset($auth_session->id)){
	  if(!isset($_GET['module'])) {
	    $_GET['module'] = '';
	  }
		if  ($_GET['module'] !== "auth" && !$installer_incomplete) {
			$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
			header('Location: ' . $siBase . '/?module=auth&view=login');
			exit;
		}

	}
}
