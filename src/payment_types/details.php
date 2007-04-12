<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pt_description",$LANG['payment_type_description']);
jsFormValidationEnd();
jsEnd();

#get the invoice id
$payment_type_id = $_GET['submit'];


#customer query
$print_payment_type = "SELECT * FROM {$tb_prefix}payment_types WHERE pt_id = $payment_type_id";
$result_payment_type = mysql_query($print_payment_type, $conn) or die(mysql_error());

$paymentType = mysql_fetch_array($result_payment_type);


if ($paymentType['pt_enabled'] == 1) {
	$paymentType['pt_enabled']  = $wording_for_enabledField;	
} else {
	$paymentType['pt_enabled'] = $wording_for_disabledField;
}

$smarty->assign('paymentType',$paymentType);
?>
