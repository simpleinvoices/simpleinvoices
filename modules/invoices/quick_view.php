<?php

/*
* Script: quick_view.php
* 	Quick view model
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*	 
* Website:
* 	http://www.simpleinvoices.or
 */

checkLogin();

#get the invoice id
$invoice_id = $_GET['invoice'];

$invoice = getInvoice($invoice_id);
$invoice_type =  getInvoiceType($invoice['type_id']);
$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$preference = getPreference($invoice['preference_id']);
$defaults = getSystemDefaults();
$invoiceItems = getInvoiceItems($invoice_id);

#Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
	$invoice_age = "$invoice_age_days {$LANG['days']}";
}
else {
    $invoice_age ="";
}

	$url_pdf = "{$http_auth}{$_SERVER['HTTP_HOST']}{$install_path}/index.php?module=invoices&view=templates/template&invoice={$invoice['id']}&action=view&location=pdf&type={$invoice['type_id']}";
	$url_pdf_encoded = urlencode($url_pdf);
	$url_for_pdf = "./include/pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&pdfname=$preference[pref_inv_wording]$invoice[id]&URL=$url_pdf_encoded";
        
	$invoice['url_for_pdf'] = $url_for_pdf;

$customFieldLabels = getCustomFieldLabels();

for($i=1;$i<=4;$i++) {
	$customField[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"read",'details_screen summary', 'details_screen','details_screen',5,':');
}
$pageActive = "invoices";

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign("customField",$customField);
$smarty -> assign("customFieldLabels",$customFieldLabels);
$smarty -> assign("invoice_age",$invoice_age);
$smarty -> assign("invoiceItems",$invoiceItems);
$smarty -> assign("defaults",$defaults);
$smarty -> assign("preference",$preference);
$smarty -> assign("biller",$biller);
$smarty -> assign("customer",$customer);
$smarty -> assign("invoice_type",$invoice_type);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("word_processor",$word_processor);
$smarty -> assign("spreadsheet",$spreadsheet);


?>
