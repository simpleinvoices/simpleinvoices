<?php



if(isset($_POST['submit'])) {
	
	insertInvoiceItem();
}
else {

$products = getActiveProducts();


$smarty -> assign("products",$products);
}
?>