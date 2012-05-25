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

if (isset($_GET['id'])) {
	$payment = new SimpleInvoices_Payment($_GET['id']);
	$paymentType = $payment->getType();
	$invoice = $payment->getInvoice();
	$invoiceType = $invoice->getType();
} else {
	throw new SimpleInvoices_Exception('Invalid payment identifier.');
}

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */

$smarty -> assign("payment",$payment->toArray());
$smarty -> assign("invoice",$invoice->toArray());
$smarty -> assign("invoiceType",$invoiceType);
$smarty -> assign("paymentType",$paymentType);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
?>