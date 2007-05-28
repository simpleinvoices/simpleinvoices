<?php



if(isset($_POST['submit'])) {
	insertInvoiceItem($_POST['invoice_id'],$_POST['quantity'],$_POST['product'],$_POST['tax_id'],$_POST['description']);
}
else {

$products = getActiveProducts();


$smarty -> assign("products",$products);
}
?>