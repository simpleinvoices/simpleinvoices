<?php
/*
 *  Script: email.php
 *      Email invoice page
 *
 *  License:
 *      GPL v3 or above
 *      
 *  Website:
 *      http://www.simpleinvoices.org
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$error = false;
$message = "Unable to process email request.";

if (empty($_GET['biller_id'])) {
    $biller = Biller::getDefaultBiller();
} else {
    $biller = Biller::select($_GET['biller_id']);
}

if (empty($biller)) {
    $error = true;
    $message = "Must specify a biller to send an e-mail";
    $biller_id = 0;
} else {
    $biller_id = $biller['id'];
}

if (empty($_GET['customer_id'])) {
    $customer = array("id" => 0, "name" => "All");
} else {
    $customer = Customer::get($_GET['customer_id']);
}
$customer_id = $customer['id'];

$do_not_filter_by_date = (empty($_GET['do_not_filter_by_date']) ? 'no' : $_GET['do_not_filter_by_date']);
$start_date = (isset($_GET['start_date']) ? $_GET['start_date'] : "");
$end_date   = (isset($_GET['end_date']  ) ? $_GET['end_date']   : "");

$show_only_unpaid = (empty($_GET['show_only_unpaid']) ? "no" : $_GET['show_only_unpaid']);

if ($_GET['stage'] == 2) {
    $export = new export();
    $export->format                = 'pdf';
    $export->module                = 'statement';
    $export->biller_id             = $biller_id;
    $export->customer_id           = $customer_id;
    $export->start_date            = $start_date;
    $export->end_date              = $end_date;
    $export->show_only_unpaid      = $show_only_unpaid;
    $export->do_not_filter_by_date = $do_not_filter_by_date;
    $export->setDownload(false);
    $export->execute();

    $attachment = $export->file_name . '.pdf';
    
    $email = new Email();
    $email->format        = 'statement';
    $email->notes         = trim($_POST['email_notes']);
    $email->from          = $_POST['email_from'];
    $email->from_friendly = $biller['name'];
    $email->to            = $_POST['email_to'];
    $email->bcc           = $_POST['email_bcc'];
    $email->subject       = $_POST['email_subject'];
    $email->attachment    = $attachment;
    $message = $email->send();

} else if ($_GET['stage'] == 3) {
    // stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error - true;
}

$smarty->assign('error'   , ($error ? "1":"0"));
$smarty->assign('message' , $message);
$smarty->assign('biller'  , $biller);
$smarty->assign('customer', $customer);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
