<?php

checkLogin();

require_once __DIR__ . '/../../include/class/siCurrencies.php';

global $LANG;
global $auth_session;

$saved = null;
$op = $_POST['op'] ?? null;

if ($op === 'insert_currency') {
	$code = trim($_POST['currency_code'] ?? '');
	$sign = trim($_POST['currency_sign'] ?? '');
	$position = $_POST['currency_position'] ?? 'left';

	if ($code === '' || $sign === '') {
		$saved = false;
	} else {
		$id = siCurrencies::insert($auth_session->domain_id, $code, $sign, $position);
		$saved = ($id > 0);
		if ($saved) {
			$bladeView->assign('redirect', 'index.php?module=currencies&view=manage');
		}
	}
} elseif ($op === 'edit_currency') {
	$id = (int) ($_GET['id'] ?? 0);
	if ($id <= 0) {
		$saved = false;
	} else {
		$code = trim($_POST['currency_code'] ?? '');
		$sign = trim($_POST['currency_sign'] ?? '');
		$position = $_POST['currency_position'] ?? 'left';

		if ($code === '' || $sign === '') {
			$saved = false;
		} else {
			$result = siCurrencies::update($id, $auth_session->domain_id, $code, $sign, $position, true);
			$saved = $result;
			if ($saved) {
				$bladeView->assign('redirect', 'index.php?module=currencies&view=manage');
			}
		}
	}
} elseif ($op === 'delete_currency') {
	$id = (int) ($_POST['currency_id'] ?? 0);
	if ($id <= 0) {
		$saved = false;
	} else {
		$currency = siCurrencies::getByIdAny($id, $auth_session->domain_id);
		if (!$currency) {
			$saved = false;
		} else {
			$result = siCurrencies::update($id, $auth_session->domain_id, $currency['currency_code'], $currency['currency_sign'], $currency['currency_position'], false);
			$saved = $result;
			if ($saved) {
				$bladeView->assign('redirect', 'index.php?module=currencies&view=manage');
			}
		}
	}
}

$bladeView->assign('saved', $saved);
$bladeView->assign('pageActive', 'currencies');
$bladeView->assign('active_tab', '#setting');
