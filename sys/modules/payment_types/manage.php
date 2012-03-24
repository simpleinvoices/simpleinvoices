<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PAYMENT_TYPES = new SimpleInvoices_PaymentTypes();

$paymentTypes = $SI_PAYMENT_TYPES->fetchAll();

$smarty -> assign('paymentTypes',$paymentTypes);

$smarty -> assign('pageActive', 'payment_type');
$smarty -> assign('active_tab', '#setting');
?>