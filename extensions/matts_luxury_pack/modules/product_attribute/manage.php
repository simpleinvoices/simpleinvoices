<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/product_attribute/manage.php
 * 	product attribute manage page
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

$smarty -> assign('pageActive', 'product_attributes');//Matt
//$smarty -> assign('subPageActive', 'product_attribute_'.$_GET['action']);//Matt
//$smarty -> assign('pageActive', "product_attribute_manage";
$smarty -> assign('active_tab', '#product');
