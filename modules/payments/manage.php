<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
//TODO - replace get..Payments with simple count - as data is got by xml.php now
$query = null;
#if coming from another page where you want to filter by just one invoice
if (!empty($_GET['id'])) {
	$query = getInvoicePayments($_GET['id']);
}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET['c_id'])) {
	$query = getCustomerPayments($_GET['c_id']);
}
#if you want to show all invoices - no filters
else {
	$query = getPayments();
}

//
$payments = progressPayments($query);

$pageActive = "payments";

$smarty->assign('pageActive', $pageActive);
$smarty -> assign("payments",$payments);

?>
