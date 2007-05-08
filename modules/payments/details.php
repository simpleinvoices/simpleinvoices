<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//TODO
/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("name","Biller name");
jsFormValidationEnd();
jsEnd();
/*end validation code*/


$sql = "SELECT {$tb_prefix}account_payments.*, {$tb_prefix}customers.name, {$tb_prefix}biller.name FROM {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  WHERE ac_inv_id = {$tb_prefix}invoices.inv_id AND {$tb_prefix}invoices.inv_customer_id = {$tb_prefix}customers.id AND {$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.id AND {$tb_prefix}account_payments.id='$_GET[inv_id]'";


$result = mysql_query($sql) or die(mysql_error());
$stuff = mysql_fetch_array($result);
$stuff['date'] = date( $config['date_format'], strtotime( $stuff['ac_date'] ) );


/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$invoice = getInvoice($stuff['ac_inv_id']);
$invoiceType = getInvoiceType($invoice['inv_type']);
$paymentType = getPaymentType($stuff['ac_payment_type']);


$smarty -> assign("stuff",$stuff);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("invoiceType",$invoiceType);
$smarty -> assign("paymentType",$paymentType);

?>
