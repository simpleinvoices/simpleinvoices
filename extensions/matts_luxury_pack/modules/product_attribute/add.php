<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/product_attribute/add.php
 * 	add a product attribute page
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
if (!isset($_POST['name']) || $_POST['name'] != "" ) {
	include("./modules/product_attribute/save.php");//??
}

$sql2= "SELECT id, name FROM ".TB_PREFIX."products_attribute_type";
$sth2 =  dbQuery($sql2);
$types = $sth2->fetchAll(PDO::FETCH_ASSOC);

$smarty -> assign("types", $types);

$smarty -> assign('pageActive', 'product_attributes');//Matt
$smarty -> assign('subPageActive', 'product_attribute_add');//Matt
//$pageActive = "product_attribute_add";
//$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');

$smarty -> assign('save',1);//$save);
