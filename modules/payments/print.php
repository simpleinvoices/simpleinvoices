<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$menu = false;
$payment = getPayment($_GET['id']);

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$invoice = getInvoice($payment['ac_inv_id']);
$biller = getBiller($payment['biller_id']);
$logo = getLogo($biller);
$logo = str_replace(" ", "%20", $logo);
$customer = getCustomer($payment['customer_id']);
$invoiceType = getInvoiceType($invoice['type_id']);
$customFieldLabels = getCustomFieldLabels();
$paymentType = getPaymentType($payment['ac_payment_type']);
$preference = getPreference($invoice['preference_id']);

$smarty -> assign("payment",$payment);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("biller",$biller);
$smarty -> assign("logo",$logo);
$smarty -> assign("customer",$customer);
$smarty -> assign("invoiceType",$invoiceType);
$smarty -> assign("paymentType",$paymentType);
$smarty -> assign("preference",$preference);
$smarty -> assign("customFieldLabels",$customFieldLabels);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
?>
