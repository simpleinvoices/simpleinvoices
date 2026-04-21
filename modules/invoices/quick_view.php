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
si_check_invoice_access($invoice);
$invoice_number_of_taxes = numberOfTaxesForInvoice($invoice_id);
$invoice_type =  getInvoiceType($invoice['type_id']);

$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$preference = getPreference($invoice['preference_id']);
$preference = InvoiceTokens::expandPreference($preference, $invoice, $biller, $customer);
$defaults = getSystemDefaults();

$invoiceobj = new invoice();
$invoiceItems = $invoiceobj->getInvoiceItems($invoice_id);

#Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
	$invoice_age = "$invoice_age_days {$LANG['days']}";
}
else {
    $invoice_age ="";
}

	$url_for_pdf = './index.php?module=export&view=invoice&id=' . rawurlencode((string) $invoice['id']) . '&format=pdf';
        
	$invoice['url_for_pdf'] = $url_for_pdf;

$customFieldLabels = getCustomFieldLabels();

for($i=1;$i<=4;$i++) {
	$customField[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"read",'summary', '','',5,':');
}


$sql = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);
$attributes = $sth->fetchAll();
$bladeView -> assign("attributes", $attributes);
//Customer accounts sections
$customerAccount = null;
$customerAccount['total'] = calc_customer_total($customer['id'],'',true);
$customerAccount['paid'] = calc_customer_paid($customer['id'],'',true);
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$bladeView -> assign('pageActive', 'invoice');
$bladeView -> assign('subPageActive', 'invoice_view');
$bladeView -> assign('active_tab', '#money');
$bladeView -> assign("customField",$customField);
$bladeView -> assign("customFieldLabels",$customFieldLabels);
$bladeView -> assign("invoice_age",$invoice_age);
$bladeView -> assign("invoice_number_of_taxes",$invoice_number_of_taxes);
$bladeView -> assign("invoiceItems",$invoiceItems);
$bladeView -> assign("defaults",$defaults);
$bladeView -> assign("preference",$preference);
$bladeView -> assign("biller",$biller);
$bladeView -> assign("customer",$customer);
$bladeView -> assign("invoice_type",$invoice_type);
$bladeView -> assign("invoice",$invoice);
$bladeView -> assign("wordprocessor", $defaults['wordprocessor'] ?? 'docx');
$bladeView -> assign("spreadsheet", $defaults['spreadsheet'] ?? 'xlsx');
$bladeView -> assign("customerAccount",$customerAccount);
?>
