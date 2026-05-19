<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//TODO
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pt_description",$LANG['payment_type_description']);
jsFormValidationEnd();
jsEnd();


#get the invoice id
$payment_type_id = (int)$_GET['id'];

$paymentType = getPaymentType($payment_type_id);
si_check_record_access($paymentType);

$bladeView->assign('paymentType',$paymentType);

$bladeView -> assign('pageActive', 'payment_type');
$subPageActive = $_GET['action'] =="view"  ? "payment_types_view" : "payment_types_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#setting');
?>
