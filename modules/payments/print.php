<?php
global $smarty;
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$menu    = false;
$payment = Payment::select($_GET['id']);

// Get Invoice preference - so can link from this screen back to the invoice
$invoice           = Invoice::getInvoice($payment['ac_inv_id']);
$biller            = Biller::select($payment['biller_id']);
$customer          = Customer::get($payment['customer_id']);
$invoiceType       = Invoice::getInvoiceType($invoice['type_id']);
$customFieldLabels = getCustomFieldLabels('',true);
$paymentType       = PaymentType::select($payment['ac_payment_type']);
$preference        = getPreference($invoice['preference_id']);
$logo              = getLogo($biller);
$logo              = str_replace(" ", "%20", $logo);

$smarty->assign("menu"             , $menu);
$smarty->assign("payment"          , $payment);
$smarty->assign("invoice"          , $invoice);
$smarty->assign("biller"           , $biller);
$smarty->assign("logo"             , $logo);
$smarty->assign("customer"         , $customer);
$smarty->assign("invoiceType"      , $invoiceType);
$smarty->assign("paymentType"      , $paymentType);
$smarty->assign("preference"       , $preference);
$smarty->assign("customFieldLabels", $customFieldLabels);
$smarty->assign('pageActive'       , 'payment');
$smarty->assign('active_tab'       , '#money');
// @formatter:on
