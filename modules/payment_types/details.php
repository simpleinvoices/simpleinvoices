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
$payment_type_id = $_GET['submit'];

$paymentType = getPaymentType($payment_type_id);


$smarty->assign('paymentType',$paymentType);
?>
