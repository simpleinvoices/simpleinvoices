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

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_CUSTOMERS = new SimpleInvoices_Db_Table_Customers();
$SI_INVOICE_TYPE = new SimpleInvoices_Db_Table_InvoiceType();
$SI_BILLER = new SimpleInvoices_Db_Table_Biller();
$SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

#get the invoice id
$invoice_id = $_GET['invoice'];


$invoice = getInvoice($invoice_id);
$invoice_type = $SI_INVOICE_TYPE->getInvoiceType($invoice['type_id']);
$customer = $SI_CUSTOMERS->getCustomerById($invoice['customer_id']);
$biller = $SI_BILLER->getBiller($invoice['biller_id']);
$preference = $SI_PREFERENCES->getPreferenceById($invoice['preference_id']);
$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
$invoiceItems = text_ui_invoice::getInvoiceItems($invoice_id);

#Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
	$invoice_age = "$invoice_age_days {$LANG['days']}";
}
else {
    $invoice_age ="";
}

	$url_for_pdf = "./pdfmaker.php?id=" . $invoice['id'];
        
	$invoice['url_for_pdf'] = $url_for_pdf;

$customFieldLabels = $SI_CUSTOM_FIELDS->getLabels();

for($i=1;$i<=4;$i++) {
	$customField[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"read",'details_screen summary', 'details_screen','details_screen',5,':');
}
$pageActive = "invoices";


//Customer accounts sections
$customerAccount = null;
$customerAccount['total'] = calc_customer_total($customer['id']);
$customerAccount['paid'] = calc_customer_paid($customer['id']);;
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

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
$smarty -> assign("customerAccount",$customerAccount);


?>
