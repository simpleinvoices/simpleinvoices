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
$invoice_id  = $_GET['id'];
$invoiceobj  = new Invoice();
$invoice     = $invoiceobj->select($invoice_id);
$preference  = getPreference($invoice['preference_id']);
$biller      = Biller::select($invoice['biller_id']);
$customer    = Customer::get($invoice['customer_id']);

$spc2us_pref   = str_replace(" ", "_", $invoice['index_name']);
$pdf_file_name = $spc2us_pref  . '.pdf';
$error = false;
$message = "Unable to process email request.";
if ($_GET['stage'] == 2 ) {
    $export = new export();
    $export->format        = "pdf";
    $export->file_location = 'file';
    $export->module        = 'invoice';
    $export->id            = $invoice_id;
    $export->execute();

    $email = new Email();
    $email->format        = 'invoice';
    $email->notes         = $_POST['email_notes'];
    $email->from          = $_POST['email_from'];
    $email->from_friendly = $biller['name'];
    $email->to            = $_POST['email_to'];
    $email->bcc           = $_POST['email_bcc'];
    $email->subject       = $_POST['email_subject'];
    $email->attachment    = $pdf_file_name;
    $message = $email->send();

} else if ($_GET['stage'] == 3 ) {
    //stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error = true;
}

$smarty->assign('error'            , ($error ? "1":"0"));
$smarty->assign('message'          , $message);
$smarty->assign('biller'           , $biller);
$smarty->assign('customer'         , $customer);
$smarty->assign('invoice'          , $invoice);
$smarty->assign('preferences'      , $preference);

$smarty->assign('pageActive', 'invoice');
$smarty->assign('active_tab', '#money');
// @formatter:on
