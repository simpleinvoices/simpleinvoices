<?php
/*
 *  Script: email.php
 *      Email invoice page
 *
 *  Last Modified:
 *      2016-08-15
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$payment  = Payment::select($_GET['id']);
$invoice  = Invoice::getInvoice($payment['ac_inv_id']);
$biller   = Biller::select($payment['biller_id']);
$customer = Customer::get($payment['customer_id']);

$error = false;
$message = "Unable to process email request.";
if ($_GET['stage'] == 2 ) {
    $export = new export();
    $export->format        = "pdf";
    $export->file_location = 'file';
    $export->module        = 'payment';
    $export->id            = $payment['id'];
    $export->execute();

    $email = new Email();
    $email->format        = 'payment';
    $email->notes         = $_POST['email_notes'];
    $email->from          = $_POST['email_from'];
    $email->from_friendly = $biller['name'];
    $email->to            = $_POST['email_to'];
    $email->bcc           = $_POST['email_bcc'];
    $email->subject       = $_POST['email_subject'];
    $email->attachment    = "payment_" . $payment['id'] . '.pdf';
    $message = $email->send();

} else if ($_GET['stage'] == 3 ) {
    //stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error = true;
}

$smarty->assign('error'     , ($error ? "1":"0"));
$smarty->assign('biller'    , $biller);
$smarty->assign('customer'  , $customer);
$smarty->assign('invoice'   , $invoice);
$smarty->assign('message'   , $message);
$smarty->assign('payment'   , $payment);

$smarty->assign('pageActive', 'payment');
$smarty->assign('active_tab', '#money');
// @formatter:on
