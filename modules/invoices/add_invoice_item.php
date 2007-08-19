<?php
/*
* Script: add_invoice_item.php
* 	add new invoice item in edit page
*
* Authors:
*	 Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */


if(isset($_POST['submit'])) {
	insertInvoiceItem($_POST['invoice_id'],$_POST['quantity'],$_POST['product'],$_POST['tax_id'],$_POST['description']);
}
else {

$products = getActiveProducts();


$smarty -> assign("products",$products);
}

$type = $_GET[type];
$smarty -> assign("type",$type);
?>
