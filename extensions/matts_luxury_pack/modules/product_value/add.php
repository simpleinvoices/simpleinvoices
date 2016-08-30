<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/product_value/add.php
 * 	add a product value page
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
if (!isset($_POST['value']) || $_POST['value'] !== '' ) {
	include("./modules/product_value/save.php");//??
}

$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
$sth =  dbQuery($sql);
$product_attributes = $sth->fetchAll();

$smarty -> assign('pageActive', 'product_values');//Matt
$smarty -> assign('subPageActive', 'product_value_add');//Matt
//$pageActive = "product_value_add";
//$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');

$smarty -> assign("product_attributes", $product_attributes);
$smarty -> assign('save',1);//$save);
