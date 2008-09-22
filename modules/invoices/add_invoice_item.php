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
		$_POST['invoice_id'],
		$_POST['quantity1'],
		$_POST['product1'],
		$_POST['tax_id'],
		$_POST['description'],
		$_POST['unit_price1']
	);
}
else {

$products = getActiveProducts();


$smarty -> assign("products",$products);
}

$type = $_GET[type];
$pageActive = "invoices";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign("type",$type);
?>
