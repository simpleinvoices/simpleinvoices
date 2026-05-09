<?php

checkLogin();

require_once __DIR__ . '/../../include/class/siCurrencies.php';

$saved = null;

$id = (int) ($_GET['id'] ?? 0);
$currency = siCurrencies::getByIdAny($id);
si_check_record_access($currency);

if (($_POST['currency_code'] ?? '') !== '') {
	include('./modules/currencies/save.php');
}

$action = $_GET['action'] ?? 'view';
if (!in_array($action, ['view', 'edit'], true)) {
	$action = 'view';
}

$bladeView->assign('currency', $currency);
$bladeView->assign('detailsAction', $action);
$bladeView->assign('pageActive', 'currencies');
$subPageActive = $action === 'view' ? 'currencies_view' : 'currencies_edit';
$bladeView->assign('subPageActive', $subPageActive);
$bladeView->assign('active_tab', '#setting');
$bladeView->assign('saved', $saved ?? null);
