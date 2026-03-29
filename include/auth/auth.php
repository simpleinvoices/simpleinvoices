<?php

/*
 * Modules excluded from auth:
 *   api/paypal - PayPal IPN callbacks originate from PayPal servers
 *   api/cron   - automated scheduler, no browser session
 */
$auth_exempt_api_views = ['paypal', 'cron'];
$is_exempt_api = ($module === 'api' && in_array($view, $auth_exempt_api_views, true));

if (!$is_exempt_api) {
	if (!isset($auth_session->id)){
	  if(!isset($_GET['module'])) {
	    $_GET['module'] = '';
	  }
		if  ($_GET['module'] !== "auth") {
			$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
			header('Location: ' . $siBase . '/?module=auth&view=login');
			exit;
		}

	}
}
