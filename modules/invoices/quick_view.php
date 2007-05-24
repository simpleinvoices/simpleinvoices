<?php

checkLogin();

#get the invoice id
$invoice_id = $_GET['submit'];

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

$customFieldLabels = getCustomFieldLabels();

for($i=1;$i<=4;$i++) {
	$customField[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"read",'details_screen summary', 'details_screen','details_screen',5,':');
}

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


?>
