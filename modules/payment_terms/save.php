<?php

checkLogin();

global $LANG;

$display_block = '';
$refresh_total = '&nbsp;';
$saved = null;

$op = $_POST['op'] ?? null;

if ($op === 'insert_payment_term') {
	$res = si_payment_term_validate_and_save_row(null);
	$saved = $res['ok'];
	$display_block = $res['message'];
	if ($res['ok']) {
		$refresh_total = "<meta http-equiv='refresh' content='1;url=index.php?module=payment_terms&amp;view=manage' />";
	}
} elseif ($op === 'edit_payment_term') {
	if (!empty($_POST['save_payment_term'])) {
		$id = (int) ($_GET['id'] ?? 0);
		if ($id <= 0) {
			$saved = false;
			$display_block = $LANG['save_payment_term_failure'] ?? 'Could not save.';
		} else {
			$res = si_payment_term_validate_and_save_row($id);
			$saved = $res['ok'];
			$display_block = $res['message'];
			if ($res['ok']) {
				$refresh_total = "<meta http-equiv='refresh' content='1;url=index.php?module=payment_terms&amp;view=manage' />";
			}
		}
	}
} elseif ($op === 'delete_payment_term') {
	$id = (int) ($_POST['term_id'] ?? 0);
	$term = getPaymentTerm($id);
	if (empty($term)) {
		$saved = false;
		$display_block = $LANG['save_payment_term_failure'] ?? 'Could not save.';
	} else {
		if (deletePaymentTerm($id)) {
			$saved = true;
			$display_block = $LANG['delete_payment_term_success'] ?? 'Deleted.';
			$refresh_total = "<meta http-equiv='refresh' content='1;url=index.php?module=payment_terms&amp;view=manage' />";
		} else {
			$saved = false;
			$display_block = $LANG['delete_payment_term_failure'] ?? 'Could not delete.';
		}
	}
}

$bladeView->assign('saved', $saved);
$bladeView->assign('display_block', $display_block);
$bladeView->assign('refresh_total', $refresh_total);
$bladeView->assign('pageActive', 'payment_term');
$bladeView->assign('active_tab', '#setting');

/**
 * @return array{ok:bool,message:string}
 */
function si_payment_term_validate_and_save_row(?int $termId): array {
	global $LANG;

	$codeRaw = trim((string) ($_POST['term_code'] ?? ''));
	$code = strtoupper(preg_replace('/[^A-Za-z0-9_]/', '_', $codeRaw));
	$code = preg_replace('/_+/', '_', $code);
	$code = trim($code, '_');
	$label = trim((string) ($_POST['term_label'] ?? ''));
	$kind = trim((string) ($_POST['calc_kind'] ?? ''));
	$sort = (int) ($_POST['sort_order'] ?? 0);

	if ($code === '' || strlen($code) > 32) {
		return ['ok' => false, 'message' => $LANG['payment_term_error_code'] ?? 'Invalid code.'];
	}
	if ($label === '' || strlen($label) > 120) {
		return ['ok' => false, 'message' => $LANG['payment_term_error_label'] ?? 'Invalid label.'];
	}
	$kinds = getPaymentTermCalcKindCodes();
	if (!in_array($kind, $kinds, true)) {
		return ['ok' => false, 'message' => $LANG['payment_term_error_kind'] ?? 'Invalid calculation type.'];
	}

	$paramStr = trim((string) ($_POST['param_int'] ?? ''));
	$param = null;
	if ($kind === 'EOM') {
		$param = null;
	} elseif ($kind === 'NET_DAYS' || $kind === 'EOM_PLUS_DAYS') {
		if ($paramStr === '' || !is_numeric($paramStr)) {
			return ['ok' => false, 'message' => $LANG['payment_term_error_param'] ?? 'Enter a number of days.'];
		}
		$param = (int) $paramStr;
		if ($param < 0 || $param > 9999) {
			return ['ok' => false, 'message' => $LANG['payment_term_error_param'] ?? 'Invalid day count.'];
		}
	} elseif ($kind === 'MFI_DAY') {
		if ($paramStr === '' || !is_numeric($paramStr)) {
			return ['ok' => false, 'message' => $LANG['payment_term_error_param_mfi'] ?? 'Enter a calendar day (1-31).'];
		}
		$param = (int) $paramStr;
		if ($param < 1 || $param > 31) {
			return ['ok' => false, 'message' => $LANG['payment_term_error_param_mfi'] ?? 'Day must be 1-31.'];
		}
	}

	if (paymentTermCodeExists($code, $termId)) {
		return ['ok' => false, 'message' => $LANG['payment_term_error_code_unique'] ?? 'That code is already in use.'];
	}

	$row = [
		'term_code' => $code,
		'term_label' => $label,
		'calc_kind' => $kind,
		'param_int' => $param,
		'sort_order' => $sort,
	];

	if ($termId === null || $termId <= 0) {
		$ok = insertPaymentTerm($row);
	} else {
		$ok = updatePaymentTerm($termId, $row);
	}

	if ($ok) {
		return ['ok' => true, 'message' => $LANG['save_payment_term_success'] ?? 'Saved.'];
	}
	return ['ok' => false, 'message' => $LANG['save_payment_term_failure'] ?? 'Could not save.'];
}
