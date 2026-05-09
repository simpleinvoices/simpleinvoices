<?php

checkLogin();

require_once __DIR__ . '/../../include/class/CurrencySignHelper.php';

$saved = null;

if (($_POST['currency_code'] ?? '') !== '') {
	include('./modules/currencies/save.php');
}

$bladeView->assign('pageActive', 'currencies');
$bladeView->assign('subPageActive', 'currencies_add');
$bladeView->assign('active_tab', '#setting');
$bladeView->assign('saved', $saved ?? null);
