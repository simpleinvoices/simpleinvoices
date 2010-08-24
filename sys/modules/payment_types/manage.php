<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$paymentTypes = getPaymentTypes();

$smarty -> assign('paymentTypes',$paymentTypes);

$smarty -> assign('pageActive', 'payment_type');
$smarty -> assign('active_tab', '#setting');
?>