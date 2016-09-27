<?php
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Add check_number field to the database if not present.
require_once "./extensions/payments/include/class/CheckNumber.php";
CheckNumber::addNewFields();

//TODO
/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("name","Biller name");
jsFormValidationEnd();
jsEnd();
/*end validation code*/

$payment = Payment::select($_GET['id']);

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$invoice     = getInvoice($payment['ac_inv_id']);
$invoiceType = Invoice::getInvoiceType($invoice['type_id']);
$paymentType = PaymentType::select($payment['ac_payment_type']);

$smarty->assign("payment"    , $payment);
$smarty->assign("invoice"    , $invoice);
$smarty->assign("invoiceType", $invoiceType);
$smarty->assign("paymentType", $paymentType);

$smarty->assign('pageActive', 'payment');
$smarty->assign('active_tab', '#money');
