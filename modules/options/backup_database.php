<?php

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();

$smarty -> assign('pageActive', 'backup');
$smarty -> assign('active_tab', '#setting');
$backup_action = 'backup_database';
$errors        = [];
$import_success = false;

$op = ($_POST['op'] ?? '');

// ── SQL backup download ────────────────────────────────────────────────────
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

// ── JSON export download ───────────────────────────────────────────────────
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $op === 'export_json') {
	requireCSRFProtection($backup_action);

	$today    = date("Ymd_His");
	$filename = "simple_invoices_data_{$today}.json";

	header('Content-Type: application/json');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Cache-Control: no-cache, no-store, must-revalidate');
	header('Pragma: no-cache');
	header('Expires: 0');

	$oBack  = new backup_db();
	$handle = fopen('php://output', 'wb');
	$oBack->export_json($handle);
	fclose($handle);
	exit();
}

// ── JSON import ────────────────────────────────────────────────────────────
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $op === 'import_json') {
	requireCSRFProtection($backup_action);

	$upload = $_FILES['json_file'] ?? null;

	if (!$upload || $upload['error'] !== UPLOAD_ERR_OK) {
		$upload_errors = [
			UPLOAD_ERR_INI_SIZE   => 'File exceeds the server upload size limit.',
			UPLOAD_ERR_FORM_SIZE  => 'File exceeds the form size limit.',
			UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
			UPLOAD_ERR_NO_FILE    => 'No file was selected.',
			UPLOAD_ERR_NO_TMP_DIR => 'Server temporary directory is missing.',
			UPLOAD_ERR_CANT_WRITE => 'Server could not write the uploaded file.',
			UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the upload.',
		];
		$errors[] = $upload_errors[$upload['error'] ?? -1] ?? 'File upload failed.';
	} else {
		$json_string = file_get_contents($upload['tmp_name']);
		if ($json_string === false) {
			$errors[] = 'Could not read the uploaded file.';
		} else {
			// Quick sanity check before handing to restore
			$decoded = json_decode($json_string, true);
			if (!is_array($decoded)) {
				$errors[] = 'The uploaded file is not valid JSON: ' . json_last_error_msg();
			} else {
				try {
					$oBack = new backup_db();
					$oBack->restore_from_json($json_string);
					$import_success = true;
				} catch (\Exception $e) {
					$errors[] = 'Import failed: ' . $e->getMessage();
					error_log('JSON import error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
				}
			}
		}
	}
}

// ── Page display: generate SQL preview ────────────────────────────────────
try {
	$oBack  = new backup_db();
	$handle = fopen('php://memory', 'r+');
	$oBack->start_backup($handle);
	rewind($handle);
	$rawSQL = stream_get_contents($handle);
	fclose($handle);

	$formatter  = new \Doctrine\SqlFormatter\SqlFormatter();
	$statements = preg_split('/;\n/', $rawSQL, -1, PREG_SPLIT_NO_EMPTY);
	$parts      = [];
	foreach ($statements as $stmt) {
		$stmt = trim($stmt);
		if ($stmt !== '') {
			$parts[] = $formatter->format($stmt . ';');
		}
	}
	$formattedSQL = implode("\n", $parts);

	$smarty->assign('formattedSQL', $formattedSQL);
	$smarty->assign('rawSQL', $rawSQL);
} catch (\Throwable $e) {
	$errors[] = 'SQL format error: ' . $e->getMessage();
	error_log('backup view_backup error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
}

$smarty->assign('backupActionToken', siNonce($backup_action));
$smarty->assign('backupErrors',      $errors);
$smarty->assign('importSuccess',     $import_success);
?>
