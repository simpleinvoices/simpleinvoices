<?php

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();

$smarty -> assign('pageActive', 'backup');
$smarty -> assign('active_tab', '#setting');
$backup_action = 'backup_database';
$errors = array();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['op'] ?? '') === 'backup_db') {
	requireCSRFProtection($backup_action);

	$today    = date("Ymd_His");
	$filename = "simple_invoices_backup_{$today}.sql";

	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Cache-Control: no-cache, no-store, must-revalidate');
	header('Pragma: no-cache');
	header('Expires: 0');

	$oBack  = new backup_db();
	$handle = fopen('php://output', 'wb');
	$oBack->start_backup($handle);
	fclose($handle);
	exit();
}

$smarty->assign('backupActionToken', siNonce($backup_action));
$smarty->assign('backupErrors', $errors);
?>
