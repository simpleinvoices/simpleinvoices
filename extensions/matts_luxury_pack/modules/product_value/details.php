<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/product_value/details.php
 * 	product value details page
 *
 * Authors:
 *	yumatechnical@gmail.com
 *
 * Last edited:
 * 	2016-08-29
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['value'] != "" ) {
	include("./modules/product_value/save.php");//??
}

#get the invoice id
$id = $_GET['id'];

$sql = "SELECT * FROM ".TB_PREFIX."products_values WHERE id = :id";
$sth =  dbQuery($sql, ':id', $id);
$product_value = $sth->fetch();
$smarty -> assign("product_value", $product_value);

$sql_attr_sel = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE id = ".$product_value['id'];
$sth_attr_sel =  dbQuery($sql_attr_sel);
$product_attribute = $sth_attr_sel->fetch();
$smarty -> assign("product_attribute", $product_attribute['name']);

$smarty -> assign('pageActive', 'product_values');//Matt
$smarty -> assign('subPageActive', 'product_value_'.$_GET['action']);
//$pageActive = "product_value_manage";//Matt
//$smarty->assign('pageActive', $pageActive);//Matt
$smarty -> assign('active_tab', '#product');

$smarty->assign('preference',$preference);

$sql_attr = "select * from ".TB_PREFIX."products_attributes";
$sth_attr =  dbquery($sql_attr);
$product_attributes = $sth_attr->fetchall();
$smarty -> assign("product_attributes", $product_attributes);
?>
