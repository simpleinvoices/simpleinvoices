<?php

checkLogin();

require_once __DIR__ . '/../../include/class/siCurrencies.php';

$currencies = siCurrencies::getForDomain();

$bladeView->assign('currencies', $currencies);
$bladeView->assign('pageActive', 'currencies');
$bladeView->assign('active_tab', '#setting');
