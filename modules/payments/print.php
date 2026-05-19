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

$bladeView -> assign("payment",$payment);
$bladeView -> assign("invoice",$invoice);
$bladeView -> assign("biller",$biller);
$bladeView -> assign("logo",$logo);
$bladeView -> assign("customer",$customer);
$bladeView -> assign("invoiceType",$invoiceType);
$bladeView -> assign("paymentType",$paymentType);
$bladeView -> assign("preference",$preference);
$bladeView -> assign("customFieldLabels",$customFieldLabels);

$bladeView -> assign('pageActive', 'payment');
$bladeView -> assign('active_tab', '#money');
?>
