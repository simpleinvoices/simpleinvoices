<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$paymentTypes = getPaymentTypes();

$bladeView -> assign('paymentTypes',$paymentTypes);

$bladeView -> assign('pageActive', 'payment_type');
$bladeView -> assign('active_tab', '#setting');
?>