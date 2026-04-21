<?php

checkLogin();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	header('Location: index.php');
	exit;
}

require_once __DIR__ . '/../../include/class/invoice_denorm.php';

$saved = false;

if (($_POST['op'] ?? '') === 'wizard_currency_sign') {
	$pref_id = (int) ($_POST['pref_id'] ?? 0);
	$sign    = $_POST['pref_currency_sign'] ?? '';
	$code    = $_POST['currency_code'] ?? '';
	$preference = getPreference($pref_id);
	if ($preference) {
		si_check_record_access($preference);
		$sql = 'UPDATE ' . TB_PREFIX . 'preferences SET pref_currency_sign = :sign, currency_code = :code WHERE pref_id = :id AND domain_id = :domain_id';
		if (dbQuery($sql, ':sign', $sign, ':code', $code, ':id', $pref_id, ':domain_id', $auth_session->domain_id)) {
			$saved = true;
			invoice_denorm::refreshAllForPreference($pref_id, $auth_session->domain_id);
			$_SESSION['wizard_currency_pref_done'] = 1;
		}
	}
}

$bladeView->assign('saved', $saved);
$bladeView->assign('pageActive', 'preference');
$bladeView->assign('active_tab', '#setting');
