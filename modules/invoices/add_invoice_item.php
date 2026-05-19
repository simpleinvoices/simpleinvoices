<?php
/*
* Script: add_invoice_item.php
* 	add new invoice item in edit page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
*/

checkLogin();

if (isset($_POST['submit'])) {
	$taxIds = (isset($_POST['tax_id']) && $_POST['tax_id'] !== '')
		? array($_POST['tax_id'])
		: array();
	insertInvoiceItem(
		$_POST['id'],
		$_POST['quantity1'],
		$_POST['product1'],
		1,
		$taxIds,
		trim((string) ($_POST['description'] ?? '')),
		$_POST['unit_price1'] ?? '',
		array()
	);
}
else {

$products = getActiveProducts();


$bladeView -> assign("products",$products);
}

$type = $_GET['type'];
$bladeView -> assign("type",$type);

$bladeView -> assign('pageActive', 'invoice');
$bladeView -> assign('active_tab', '#money');
?>