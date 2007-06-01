<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

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

$payments = progressPayments($query);

getRicoLiveGrid("rico_payment","{ type:'number', decPlaces:0, ClassName:'alignleft' },{ type:'number', decPlaces:0, ClassName:'alignleft' },,,{ type:'number', decPlaces:2, ClassName:'alignleft' }");

$smarty -> assign("payments",$payments);

?>
