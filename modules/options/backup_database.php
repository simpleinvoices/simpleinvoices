<?php

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();

$smarty -> assign('pageActive', 'backup');
$smarty -> assign('active_tab', '#setting');
$backup_action = 'backup_database';
$errors = array();

$op = ($_POST['op'] ?? '');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $op === 'backup_db') {
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

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $op === 'view_backup') {
	requireCSRFProtection($backup_action);

	try {
		$oBack  = new backup_db();
		$handle = fopen('php://memory', 'r+');
		$oBack->start_backup($handle);
		rewind($handle);
		$sql = stream_get_contents($handle);
		fclose($handle);

		$formatter  = new \Doctrine\SqlFormatter\SqlFormatter();
		$statements = preg_split('/;\n/', $sql, -1, PREG_SPLIT_NO_EMPTY);
		$parts      = [];
		foreach ($statements as $stmt) {
			$stmt = trim($stmt);
			if ($stmt !== '') {
				$parts[] = $formatter->format($stmt . ';');
			}
		}
		$formattedSQL = implode("\n", $parts);

		$smarty->assign('formattedSQL', $formattedSQL);
	} catch (\Throwable $e) {
		$errors[] = 'SQL format error: ' . $e->getMessage();
		error_log('backup view_backup error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
	}
}

$smarty->assign('backupActionToken', siNonce($backup_action));
$smarty->assign('backupErrors', $errors);
?>
