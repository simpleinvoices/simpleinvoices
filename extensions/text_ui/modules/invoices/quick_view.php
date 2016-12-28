<?php
/*
 * Script: quick_view.php
 *     Quick view model
 *
 * Authors:
 *     Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 * Last edited:
 *   2016-02-11 Rich Rowley to make it work!
 *      2008-01-03
 *
 * License:
 *     GPL v2 or above
 *     
 * Website:
 *     http://www.simpleinvoices.or
 */
global $LANG, $smarty;

checkLogin();

// @formatter:off
$invoice_id   = $_GET['invoice'];
$invoice      = Invoice::getInvoice($invoice_id);
$invoice_type = Invoice::getInvoiceType($invoice['type_id']);
$customer     = Customer::get($invoice['customer_id']);
$biller       = Biller::select($invoice['biller_id']);
$preference   = Preferences::getPreference($invoice['preference_id']);
$defaults     = getSystemDefaults();

$invoiceItems  = TextUiInvoice::getInvoiceItems($invoice_id);

#Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoice_age_days = number_format((strtotime(date('Y-m-d')) - 
                                       strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
    $invoice_age = "$invoice_age_days {$LANG['days']}";
}
else {
    $invoice_age ="";
}

$url_for_pdf = "pdfmaker.php?id=" . $invoice['id'];
        
$invoice['url_for_pdf'] = $url_for_pdf;

$customFieldLabels = getCustomFieldLabels('',true);

$customField = array();
for($i=1;$i<=4;$i++) {
    $customField[$i] = CustomFields::show_custom_field("invoice_cf$i"  , $invoice["custom_field$i"],
                                                       "read"          , 'details_screen summary'  ,
                                                       'details_screen', 'details_screen'          ,
                                                       5               , ':');
}
$pageActive = "invoices";

//Customer accounts sections
$customerAccount = null;
$customerAccount['total'] = Customer::calc_customer_total($customer['id']);
$customerAccount['paid']  = Payment::calc_customer_paid($customer['id']);
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$word_processor = null;
$spreadsheet = null;

$smarty->assign('pageActive'       , $pageActive);
$smarty->assign("customField"      , $customField);
$smarty->assign("customFieldLabels", $customFieldLabels);
$smarty->assign("invoice_age"      , $invoice_age);
$smarty->assign("invoiceItems"     , $invoiceItems);
$smarty->assign("defaults"         , $defaults);
$smarty->assign("preference"       , $preference);
$smarty->assign("biller"           , $biller);
$smarty->assign("customer"         , $customer);
$smarty->assign("invoice_type"     , $invoice_type);
$smarty->assign("invoice"          , $invoice);
$smarty->assign("word_processor"   , $word_processor);
$smarty->assign("spreadsheet"      , $spreadsheet);
$smarty->assign("customerAccount"  , $customerAccount);
// @formatter:on
