<?php

checkLogin();

$bladeView->assign('calcKinds', getPaymentTermCalcKindCodes());
$bladeView->assign('pageActive', 'payment_term');
$bladeView->assign('subPageActive', 'payment_terms_add');
$bladeView->assign('active_tab', '#setting');
