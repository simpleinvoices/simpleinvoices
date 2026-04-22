<?php

checkLogin();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	header('Location: index.php');
	exit;
}

require_once __DIR__ . '/../../include/class/invoice_denorm.php';

$saved = false;
$defaults = getSystemDefaults();

if (($_POST['op'] ?? '') === 'wizard_currency_sign') {
	$pref_id         = (int) ($_POST['pref_id'] ?? 0);
	$sign            = $_POST['pref_currency_sign'] ?? '';
	$code            = $_POST['currency_code'] ?? '';
	$payment_term_id = isset($_POST['payment_term_id']) && $_POST['payment_term_id'] !== ''
		? (int) $_POST['payment_term_id'] : null;
	$preference = getPreference($pref_id);
	if ($preference) {
		si_check_record_access($preference);
		$sql = 'UPDATE ' . TB_PREFIX . 'preferences SET pref_currency_sign = :sign, currency_code = :code, payment_term_id = :payment_term_id WHERE pref_id = :id AND domain_id = :domain_id';
		if (dbQuery($sql, ':sign', $sign, ':code', $code, ':payment_term_id', $payment_term_id, ':id', $pref_id, ':domain_id', $auth_session->domain_id)) {
			$default_biller_id = (int) (($defaults['biller'] ?? 0));
			if ($default_biller_id <= 0) {
				$billers = getBillers($auth_session->domain_id) ?: [];
				$default_biller_id = (int) ($billers[0]['id'] ?? 0);
			}
			if ($default_biller_id > 0) {
				$biller = getBiller($default_biller_id, $auth_session->domain_id);
				if ($biller) {
					si_check_record_access($biller);
					dbQuery(
						'UPDATE ' . TB_PREFIX . 'biller
						 SET bank_account_name = :bank_account_name,
						     bank_name = :bank_name,
						     bank_swift_bic = :bank_swift_bic,
						     bank_account_number = :bank_account_number,
						     bank_routing_sort_code = :bank_routing_sort_code
						 WHERE id = :id AND domain_id = :domain_id',
						':bank_account_name', trim((string) ($_POST['bank_account_name'] ?? '')),
						':bank_name', trim((string) ($_POST['bank_name'] ?? '')),
						':bank_swift_bic', trim((string) ($_POST['bank_swift_bic'] ?? '')),
						':bank_account_number', trim((string) ($_POST['bank_account_number'] ?? '')),
						':bank_routing_sort_code', trim((string) ($_POST['bank_routing_sort_code'] ?? '')),
						':id', $default_biller_id,
						':domain_id', $auth_session->domain_id
					);
				}
			}
			$saved = true;
			invoice_denorm::refreshAllForPreference($pref_id, $auth_session->domain_id);
			$_SESSION['wizard_currency_pref_done'] = 1;
			$_SESSION['wizard_invoice_prefs_done'] = 1;
		}
	}
}

$bladeView->assign('saved', $saved);
$bladeView->assign('pageActive', 'preference');
$bladeView->assign('active_tab', '#setting');
