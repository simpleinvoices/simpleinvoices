<?php

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();

$smarty -> assign('pageActive', 'backup');
$smarty -> assign('active_tab', '#setting');
$backup_action = 'backup_database';
$messages = array();
$errors = array();
$backup_results = array();
$backup_file = '';
$can_backup = is_dir('./tmp/database_backups') ? is_writable('./tmp/database_backups') : is_writable('./tmp');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['op'] ?? '') === 'backup_db') {
	requireCSRFProtection($backup_action);

	try {
		$today = date("Ymd_His");
		$oBack = new backup_db();
		$oBack->filename = "./tmp/database_backups/simple_invoices_backup_$today.sql";
		$oBack->start_backup();

		$backup_results = is_array($oBack->output) ? $oBack->output : array();
		$backup_file = $oBack->filename;
		$messages[] = sprintf($LANG['backup_done'], $oBack->filename);
	} catch (Throwable $e) {
		$errors[] = $e->getMessage();
	}
}
$smarty->assign('backupActionToken', siNonce($backup_action));
$smarty->assign('backupMessages', $messages);
$smarty->assign('backupErrors', $errors);
$smarty->assign('backupResults', $backup_results);
$smarty->assign('backupFile', $backup_file);
$smarty->assign('backupDirectoryWritable', $can_backup);
?>
