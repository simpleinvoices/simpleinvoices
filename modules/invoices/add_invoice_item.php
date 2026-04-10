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


if(isset($_POST['submit'])) {
	insertInvoiceItem(
		$_POST['id'],
		$_POST['quantity1'],
		$_POST['product1'],
		$_POST['tax_id'],
		trim($_POST['description']),
		$_POST['unit_price1']
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