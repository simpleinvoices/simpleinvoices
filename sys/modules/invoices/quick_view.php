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
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

if (!isset($_GET['id'])) {
	throw new SimpleInvoices_Exception('Invalid invoice');
}

$invoice = new SimpleInvoices_Invoice($_GET['id']);
$invoice_number_of_taxes = $invoice->getNumberOfTaxes();
$invoice_type = $invoice->getType();
$customer = $invoice->getCustomer();
$biller = $invoice->getBiller();
$preference = $invoice->getPreference();

$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
$invoiceItems = invoice::getInvoiceItems($invoice->getId());

    $eway_check = new eway();
    $eway_check->invoice = $invoice->toArray();
    $eway_pre_check = $eway_check->pre_check();
    
	#Invoice Age - number of days - start
	if ($invoice->owing > 0 ) {
	    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice->calc_date)) / (60 * 60 * 24),0);
		$invoice_age = "$invoice_age_days {$LANG['days']}";
	}
	else {
	    $invoice_age ="";
	}

	$url_for_pdf = "./index.php?module=export&view=pdf&id=" . $invoice->getId();

	$invoice->url_for_pdf = $url_for_pdf;

    $customFieldLabels = $SI_CUSTOM_FIELDS->getLabels();
    $customFieldDisplay = $SI_CUSTOM_FIELDS->getDisplay();

    $invoice_array = $invoice->toArray();
for($i=1;$i<=4;$i++) {
	$customField[$i] = show_custom_field("invoice_cf$i",$invoice_array["custom_field$i"],"read",'details_screen summary', 'details_screen','details_screen',5,':');
}

//Set locked status on locked invoices
if ($invoice->status=='final') {
    $invoicelocked = 'true';
} else {
    $invoicelocked = 'false';
}

//Customer accounts sections
$customerAccount = null;
$customerAccount['total'] = $customer->getTotal();
$customerAccount['paid'] = $customer->getPaidAmount();
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$smarty -> assign('pageActive', 'invoice');
$smarty -> assign('subPageActive', 'invoice_view');
$smarty -> assign('active_tab', '#money');
$smarty -> assign("customField",$customField);
$smarty -> assign("customFieldLabels",$customFieldLabels);
$smarty -> assign("customFieldDisplay",$customFieldDisplay);
$smarty -> assign("invoice_age",$invoice_age);
$smarty -> assign("invoice_number_of_taxes",$invoice_number_of_taxes);
$smarty -> assign("invoiceItems",$invoiceItems);
$smarty -> assign("defaults",$defaults);
$smarty -> assign("preference",$preference);
$smarty -> assign("biller",$biller->toArray());
$smarty -> assign("customer",$customer->toArray());
$smarty -> assign("invoice_type",$invoice_type);
$smarty -> assign("invoice",$invoice->toArray());
$smarty -> assign("wordprocessor",$config->export->wordprocessor);
$smarty -> assign("spreadsheet",$config->export->spreadsheet);
$smarty -> assign("customerAccount",$customerAccount);
$smarty -> assign("eway_pre_check",$eway_pre_check);
$smarty -> assign("invoicelocked", $invoicelocked);
