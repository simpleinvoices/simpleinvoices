<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (!empty($_POST['value'])) {
	include("./modules/product_value/save.php");
}

#get the invoice id
$id = $_GET['id'];

$sql = "SELECT * FROM ".TB_PREFIX."products_values WHERE id = :id";
$sth =  dbQuery($sql, ':id', $id);
$product_value = $sth->fetch();
$smarty->assign("product_value", $product_value);

$sql_attr_sel = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE id = ".$product_value['id'];
$sth_attr_sel =  dbQuery($sql_attr_sel);
$product_attribute = $sth_attr_sel->fetch();
$smarty->assign("product_attribute", $product_attribute['name']);

$pageActive = "product_value_manage";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('active_tab', '#product');

$smarty->assign('preference',$preference);

$sql_attr = "select * from ".TB_PREFIX."products_attributes";
$sth_attr =  dbquery($sql_attr);
$product_attributes = $sth_attr->fetchall();
$smarty->assign("product_attributes", $product_attributes);
