<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['value'] != "" ) {
	include("./modules/product_value/save.php");
}

#get the id
$id = $_GET['id'];

$domain_id = domain_id::get();

$sql = "SELECT * FROM ".TB_PREFIX."products_values WHERE id = :id AND domain_id = :domain_id";
$sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
$product_value = $sth->fetch();
$bladeView -> assign("product_value", $product_value);

$sql_attr_sel = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE id = :id AND domain_id = :domain_id";
$sth_attr_sel = dbQuery($sql_attr_sel, ':id', $product_value['attribute_id'], ':domain_id', $domain_id);
$product_attribute = $sth_attr_sel->fetch();
$bladeView -> assign("product_attribute", $product_attribute['name']);


$pageActive = "product_value_manage";
$bladeView->assign('pageActive', $pageActive);
$bladeView -> assign('active_tab', '#product');

$bladeView->assign('preference',$preference);

$sql_attr = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE domain_id = :domain_id";
$sth_attr = dbQuery($sql_attr, ':domain_id', $domain_id);
$product_attributes = $sth_attr->fetchAll();
$bladeView -> assign("product_attributes", $product_attributes);
?>
