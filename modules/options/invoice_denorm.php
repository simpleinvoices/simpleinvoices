<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

global $auth_session;

$csrf_action = 'invoice_denorm';
$flash_msg   = null;
$flash_type  = 'info';
$verify      = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
	$op = $_POST['op'] ?? '';
	if ($op === 'verify_denorm') {
		requireCSRFProtection($csrf_action);
		$verify = invoice_denorm::verifyDomain($auth_session->domain_id);
	} elseif ($op === 'rebuild_denorm') {
		requireCSRFProtection($csrf_action);
		invoice_denorm::rebuildDomain($auth_session->domain_id);
		dashboard_cache_clear((int) $auth_session->domain_id);
		$flash_msg  = $LANG['invoice_denorm_rebuild_done'] ?? '';
		$flash_type = 'success';
		$verify     = invoice_denorm::verifyDomain($auth_session->domain_id);
	}
}

$bladeView->assign('invoiceDenormCsrfToken', siNonce($csrf_action));
$bladeView->assign('invoice_denorm_flash', $flash_msg);
$bladeView->assign('invoice_denorm_flash_type', $flash_type);
$bladeView->assign('invoice_denorm_verify', $verify);
$bladeView->assign('pageActive', 'setting');
$bladeView->assign('active_tab', '#setting');
