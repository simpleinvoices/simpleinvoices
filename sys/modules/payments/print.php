<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

$menu = false;

if(!isset($_GET['id'])) {
	throw new SimpleInvoices_Exception('Invalid payment identifier.');
}

$payment = new SimpleInvoices_Payment($_GET['id']);

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$invoice = $payment->getInvoice();
$biller = $invoice->getBiller();
$logo = $biller->getLogo();
$logo = str_replace(" ", "%20", $logo);
$customer = $invoice->getCustomer();
$invoiceType = $invoice->getType();
$customFieldLabels = $SI_CUSTOM_FIELDS->getLabels();
$paymentType = $payment->getType();
$preference = $invoice->getPreference();

$smarty -> assign("payment",$payment->toArray());
$smarty -> assign("invoice",$invoice->toArray());
$smarty -> assign("biller",$biller->toArray());
$smarty -> assign("logo",$logo);
$smarty -> assign("customer",$customer->toArray());
$smarty -> assign("invoiceType",$invoiceType);
$smarty -> assign("paymentType",$paymentType);
$smarty -> assign("preference",$preference);
$smarty -> assign("customFieldLabels",$customFieldLabels);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
