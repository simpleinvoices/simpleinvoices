<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$payments = null; $inv_id = null; $c_id = null;
$domain_id = $auth_session->domain_id;

#if coming from another page where you want to filter by just one invoice
if (!empty($_GET['id'])) {
	$inv_id = $_GET['id'];
	$invoice = getInvoice($_GET['id']);
	$preference = getPreference($invoice['preference_id']);
	$subPageActive = "payment_filter_invoice";
	// Lightweight existence check — actual data comes from xml.php with pagination
	$sth = dbQuery(
		"SELECT COUNT(*) AS cnt FROM ".TB_PREFIX."payment WHERE ac_inv_id = :id AND domain_id = :domain_id",
		':id', $inv_id, ':domain_id', $domain_id
	);
	$row = $sth->fetch();
	$payments = ((int) $row['cnt'] > 0) ? [true] : null;
}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET['c_id'])) {
	$c_id = $_GET['c_id'];
	$customer = getCustomer($_GET['c_id']);
	$subPageActive = "payment_filter_customer";
	// Lightweight existence check — actual data comes from xml.php with pagination
	$sth = dbQuery(
		"SELECT COUNT(*) AS cnt
		 FROM ".TB_PREFIX."payment ap
		 INNER JOIN ".TB_PREFIX."invoices iv ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
		 WHERE iv.customer_id = :cid AND ap.domain_id = :domain_id",
		':cid', $c_id, ':domain_id', $domain_id
	);
	$row = $sth->fetch();
	$payments = ((int) $row['cnt'] > 0) ? [true] : null;
}
#if you want to show all payments - no filters
else {
	$subPageActive = "payment_manage";
	// Lightweight existence check — actual data comes from xml.php with pagination
	$p = new payment();
	$payments = ($p->count() > 0) ? [true] : null;
}

$bladeView -> assign("payments",$payments);
$bladeView -> assign("preference",$preference);
$bladeView -> assign("customer",$customer);

$bladeView -> assign("c_id",$c_id);
$bladeView -> assign("inv_id",$inv_id);

$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('pageActive', 'payment');
$bladeView -> assign('active_tab', '#money');
?>
