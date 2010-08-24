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

/*
if(isset($_POST['submit'])) {
	matrix_invoice::insertInvoiceItem($_POST['invoice_id'],$_POST['quantity1'],$_POST['product1'],$_POST['tax_id'],$_POST['description'],$_POST["attr1"],$_POST["attr2"], $_POST["attr3"], $_POST["unit_price1"]);
}
else {

$products = getActiveProducts();


$smarty -> assign("products",$products);
}

$type = $_GET[type];
$pageActive = "invoices";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign("type",$type);

$sql_prod = "select product_id as PID, (select count(product_id) from ".TB_PREFIX."products_matrix where product_id = PID ) as count from ".TB_PREFIX."products_matrix ORDER BY count desc LIMIT 1;";
$sth_prod =  dbQuery($sql_prod);
$number_of_products = $sth_prod->fetchAll();

$smarty -> assign("number_of_attributes", $number_of_products['0']['count']);


*/

?>
