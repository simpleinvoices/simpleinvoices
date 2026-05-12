<?php

checkLogin();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	header('Location: index.php');
	exit;
}

require_once __DIR__ . '/../../include/class/invoice_denorm.php';
require_once __DIR__ . '/../../include/class/siCurrencies.php';

$saved = false;
$defaults = getSystemDefaults();

if (($_POST['op'] ?? '') === 'wizard_currency_sign') {
	$pref_id         = (int) ($_POST['pref_id'] ?? 0);
	$code            = trim($_POST['currency_code'] ?? '');
	$currency_id     = (int) ($_POST['currency_id'] ?? 0);
	$payment_term_id = isset($_POST['payment_term_id']) && $_POST['payment_term_id'] !== ''
		? (int) $_POST['payment_term_id'] : null;

	if ($currency_id > 0) {
		$currRow = siCurrencies::getById($currency_id, $auth_session->domain_id);
		if (!$currRow) {
			$currency_id = 0;
		}
	}

	if ($currency_id === 0) {
		$sign     = CurrencySignHelper::forDisplay($_POST['currency_sign_value'] ?? '');
		$position = CurrencySignHelper::defaultPositionForSign($sign, $code);
		$currRow  = siCurrencies::findOrCreate($auth_session->domain_id, $sign, $code, $position);
		$currency_id = $currRow ? (int) $currRow['id'] : 0;
	}

	$preference = getPreference($pref_id);
	if ($preference) {
		si_check_record_access($preference);
		$sql = 'UPDATE ' . TB_PREFIX . 'preferences SET currency_id = :currency_id, payment_term_id = :payment_term_id WHERE pref_id = :id AND domain_id = :domain_id';
		if (dbQuery($sql, ':currency_id', $currency_id ?: null, ':payment_term_id', $payment_term_id, ':id', $pref_id, ':domain_id', $auth_session->domain_id)) {
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

			$start_num = trim($_POST['set_starting_invoice_number'] ?? '');
			if ($start_num !== '' && ctype_digit($start_num)) {
				$new_start = (int) $start_num;
				$index_group = (int) ($preference['index_group'] ?? 1);

				if ($new_start >= 1) {
					$sql_max = "SELECT MAX(index_id) AS max_idx
						FROM " . TB_PREFIX . "invoices
						WHERE domain_id = :domain_id AND preference_id IN (
							SELECT pref_id FROM " . TB_PREFIX . "preferences
							WHERE index_group = :index_group AND domain_id = :domain_id2
						)";
					$sth = dbQuery($sql_max,
						':domain_id', $auth_session->domain_id,
						':index_group', $index_group,
						':domain_id2', $auth_session->domain_id);
					$row = $sth->fetch();
					$max_existing = (int) ($row['max_idx'] ?? 0);

					if ($new_start > $max_existing) {
						$set_id = $new_start - 1;
						$sql_idx = "SELECT id FROM " . TB_PREFIX . "index
							WHERE node = 'invoice' AND sub_node = :sub_node
							AND domain_id = :domain_id";
						$sth_idx = dbQuery($sql_idx,
							':sub_node', $index_group,
							':domain_id', $auth_session->domain_id);
						$exists = $sth_idx->fetch();

						if ($exists) {
							dbQuery(
								"UPDATE " . TB_PREFIX . "index SET id = :id
								WHERE node = 'invoice' AND sub_node = :sub_node AND domain_id = :domain_id",
								':id', $set_id,
								':sub_node', $index_group,
								':domain_id', $auth_session->domain_id
							);
						} else {
							dbQuery(
								"INSERT INTO " . TB_PREFIX . "index (id, node, sub_node, sub_node_2, domain_id)
								VALUES (:id, 'invoice', :sub_node, 0, :domain_id)",
								':id', $set_id,
								':sub_node', $index_group,
								':domain_id', $auth_session->domain_id
							);
						}
					}
				}
			}

			$_SESSION['wizard_currency_pref_done'] = 1;
			$_SESSION['wizard_invoice_prefs_done'] = 1;
		}
	}
}

$bladeView->assign('saved', $saved);
$bladeView->assign('pageActive', 'preference');
$bladeView->assign('active_tab', '#setting');
