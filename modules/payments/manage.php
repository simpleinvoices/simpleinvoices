<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
//TODO - replace get..Payments with simple count - as data is got by xml.php now
$query = null;$inv_id = null;$c_id = null;
#if coming from another page where you want to filter by just one invoice
if (!empty($_GET['id'])) {
	$inv_id = $_GET['id'];
	$query = getInvoicePayments($_GET['id']);
	$invoice = getInvoice($_GET['id']);
	$preference = getPreference($invoice['preference_id']);
    $subPageActive = "payment_filter_invoice";
}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET['c_id'])) {
	$c_id = $_GET['c_id'];
	$query = getCustomerPayments($_GET['c_id']);
    $customer = getCustomer($_GET['c_id']);
    $subPageActive = "payment_filter_customer";
}
#if you want to show all invoices - no filters
else {
	$query = getPayments();
    $subPageActive = "payment_manage";
}

$payments = progressPayments($query);

$smarty -> assign("payments",$payments);
$smarty -> assign("preference",$preference);
$smarty -> assign("customer",$customer);

$smarty -> assign("c_id",$c_id);
$smarty -> assign("inv_id",$inv_id);

$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
?>
