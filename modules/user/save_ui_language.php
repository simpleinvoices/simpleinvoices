<?php

$menu = false;

checkLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: index.php?module=index&view=index');
	exit;
}

requireCSRFProtection('ui_language');

$lang = isset($_POST['preferred_language']) ? trim((string) $_POST['preferred_language']) : '';
$dbVal = null;
if ($lang !== '') {
	if (!si_lang_folder_exists($lang)) {
		header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?module=index&view=index'));
		exit;
	}
	$dbVal = $lang;
}

global $auth_session;
$uid      = (int) ($auth_session->id ?? 0);
$domainId = (int) ($auth_session->domain_id ?? 0);
if ($uid < 1 || $domainId < 1) {
	header('Location: index.php?module=index&view=index');
	exit;
}

if (checkFieldExists(TB_PREFIX . 'user', 'preferred_language')) {
	dbQuery(
		'UPDATE ' . TB_PREFIX . 'user SET preferred_language = :p WHERE id = :id AND domain_id = :d',
		':p',
		$dbVal,
		':id',
		$uid,
		':d',
		$domainId
	);
}
$auth_session->ui_language = $dbVal === null ? '' : $dbVal;

$back = $_SERVER['HTTP_REFERER'] ?? '';
if ($back === '' || !preg_match('#^https?://#i', $back)) {
	$back = 'index.php?module=index&view=index';
}
header('Location: ' . $back);
exit;
