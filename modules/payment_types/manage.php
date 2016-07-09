<?php
global $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
checkLogin();

$paymentTypes = getPaymentTypes();

$smarty->assign('paymentTypes',$paymentTypes);

$smarty->assign('pageActive', 'payment_type');
$smarty->assign('active_tab', '#setting');
