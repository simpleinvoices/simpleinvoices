<?php

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();

$op = ($_POST['op'] ?? $_GET['op'] ?? '');

$backup_action = 'backup_database';

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

	header('Content-Type: application/octet-stream');
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

header('Content-Type: application/json');

// ── AJAX: generate SQL preview (lazy, on demand) ───────────────────────────
if ($op === 'view_sql') {
	try {
		$oBack  = new backup_db();
		$handle = fopen('php://memory', 'r+');
		$oBack->start_backup($handle);
		rewind($handle);
		$rawSQL = stream_get_contents($handle);
		fclose($handle);

		$formatter   = new \Doctrine\SqlFormatter\SqlFormatter(
			new \Doctrine\SqlFormatter\HtmlHighlighter([], false)
		);
		$formattedHTML = $formatter->format($rawSQL);

		echo json_encode(
			['ok' => true, 'html' => $formattedHTML, 'raw' => $rawSQL],
			JSON_INVALID_UTF8_SUBSTITUTE
		);
	} catch (\Throwable $e) {
		error_log('backup ajax view_sql error: ' . $e->getMessage());
		echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
	}
	exit();
}

// ── AJAX: generate JSON preview (lazy, on demand) ─────────────────────────
if ($op === 'view_json') {
	try {
		$oBack  = new backup_db();
		$handle = fopen('php://memory', 'r+');
		$oBack->export_json($handle);
		rewind($handle);
		$rawJSON = stream_get_contents($handle);
		fclose($handle);

		// Embed the JSON data directly rather than double-encoding it as a string value,
		// which can silently fail on large payloads or non-UTF-8 data.
		if ($rawJSON === false || $rawJSON === '') {
			echo json_encode(['ok' => false, 'error' => 'Failed to generate JSON export']);
		} else {
			echo '{"ok":true,"data":' . $rawJSON . '}';
		}
	} catch (\Throwable $e) {
		error_log('backup ajax view_json error: ' . $e->getMessage());
		echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
	}
	exit();
}

echo json_encode(['ok' => false, 'error' => 'Unknown operation']);
