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
$payment_type_id = $_GET['id'];

$paymentType = getPaymentType($payment_type_id);

$smarty->assign('paymentType',$paymentType);

$smarty -> assign('pageActive', 'payment_type');
$smarty -> assign('active_tab', '#setting');
