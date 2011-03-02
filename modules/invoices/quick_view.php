<?php

/*
* Script: quick_view.php
* 	Quick view model
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ap.Muthu
*
* Last edited:
* 	 2008-01-03
*
* License:
*	 GPL v2 or above
*	 
* Website:
* 	http://www.simpleinvoices.or
 */

checkLogin();

#get the invoice id
$invoice_id = $_GET['id'];


$invoice = getInvoice($invoice_id);
$invoice_number_of_taxes = numberOfTaxesForInvoice($invoice_id);
$invoice_type =  getInvoiceType($invoice['type_id']);

$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$preference = getPreference($invoice['preference_id']);
$defaults = getSystemDefaults();
$invoiceItems = invoice::getInvoiceItems($invoice_id);

    $eway_check = new eway();
    $eway_check->invoice = $invoice;
    $eway_pre_check = $eway_check->pre_check();

#Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
	$invoice_age = "$invoice_age_days {$LANG['days']}";
}
else {
    $invoice_age ="";
}

	$url_for_pdf = "./index.php?module=export&view=pdf&id=" . $invoice['id'];
        
	$invoice['url_for_pdf'] = $url_for_pdf;

$customFieldLabels = getCustomFieldLabels();

for($i=1;$i<=4;$i++) {
	$customField[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"read",'details_screen summary', 'details_screen','details_screen',5,':');
}


//Customer accounts sections
$customerAccount = null;
$customerAccount['total'] = calc_customer_total($customer['id']);
$customerAccount['paid'] = calc_customer_paid($customer['id']);
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$smarty -> assign('pageActive', 'invoice');
$smarty -> assign('subPageActive', 'invoice_view');
$smarty -> assign('active_tab', '#money');
$smarty -> assign("customField",$customField);
$smarty -> assign("customFieldLabels",$customFieldLabels);
$smarty -> assign("invoice_age",$invoice_age);
$smarty -> assign("invoice_number_of_taxes",$invoice_number_of_taxes);
$smarty -> assign("invoiceItems",$invoiceItems);
$smarty -> assign("defaults",$defaults);
$smarty -> assign("preference",$preference);
$smarty -> assign("biller",$biller);
$smarty -> assign("customer",$customer);
$smarty -> assign("invoice_type",$invoice_type);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("wordprocessor",$config->export->wordprocessor);
$smarty -> assign("spreadsheet",$config->export->spreadsheet);
$smarty -> assign("customerAccount",$customerAccount);
$smarty -> assign("eway_pre_check",$eway_pre_check);
?>
