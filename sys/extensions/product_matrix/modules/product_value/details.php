<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['value'] != "" ) {
	include("./extensions/product_matrix/modules/product_value/save.php");
}

#get the invoice id
$id = $_GET['id'];

$sql = "select * from ".TB_PREFIX."products_values where id = $id";
$sth =  dbQuery($sql);
$product_value = $sth->fetch();
$smarty -> assign("product_value", $product_value);

$sql_attr_sel = "select * from ".TB_PREFIX."products_attributes where id = ".$product_value['id'];
$sth_attr_sel =  dbQuery($sql_attr_sel);
$product_attribute = $sth_attr_sel->fetch();
$smarty -> assign("product_attribute", $product_attribute['name']);


$pageActive = "options";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('preference',$preference);

$sql_attr = "select * from ".TB_PREFIX."products_attributes";
$sth_attr =  dbquery($sql_attr);
$product_attributes = $sth_attr->fetchall();
$smarty -> assign("product_attributes", $product_attributes);
?>
