<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['name'] != "" ) {
	include("./extensions/product_matrix/modules/product_attribute/save.php");
}

#get the invoice id
$id = $_GET['id'];

$sql_prod = "select * from ".TB_PREFIX."products_attributes where id = $id;";
$sth_prod =  dbQuery($sql_prod);
$product_attribute = $sth_prod->fetch();

$pageActive = "options";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('product_attribute',$product_attribute);

?>
