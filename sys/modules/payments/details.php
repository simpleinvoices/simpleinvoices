<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PAYMENT_TYPES = new SimpleInvoices_Db_Table_PaymentTypes();
$SI_INVOICE_TYPE = new SimpleInvoices_Db_Table_InvoiceType();

//TODO
/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("name","Biller name");
jsFormValidationEnd();
jsEnd();
/*end validation code*/

$payment = getPayment($_GET['id']);

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$invoice = getInvoice($payment['ac_inv_id']);
$invoiceType = $SI_INVOICE_TYPE->getInvoiceType($invoice['type_id']);
$paymentType = $SI_PAYMENT_TYPES->find($payment['ac_payment_type']);


$smarty -> assign("payment",$payment);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("invoiceType",$invoiceType);
$smarty -> assign("paymentType",$paymentType);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
?>