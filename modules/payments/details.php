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

$payment = getPayment((int)$_GET['id']);
si_check_record_access($payment);

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$invoice = getInvoice($payment['ac_inv_id']);
$biller = getBiller($payment['biller_id']);
$customer = getCustomer($payment['customer_id']);
$preference = getPreference($invoice['preference_id']);
$invoiceType = getInvoiceType($invoice['type_id']);
$paymentType = getPaymentType($payment['ac_payment_type']);

$bladeView -> assign("payment", $payment);
$bladeView -> assign("invoice", $invoice);
$bladeView -> assign("biller", $biller);
$bladeView -> assign("customer", $customer);
$bladeView -> assign("preference", $preference);
$bladeView -> assign("invoiceType", $invoiceType);
$bladeView -> assign("paymentType", $paymentType);

$bladeView -> assign('pageActive', 'payment');
$bladeView -> assign('active_tab', '#money');
?>