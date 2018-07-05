<?php
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (!empty($_POST['value'])) {
	include("modules/product_value/save.php");
}

$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
$sth =  dbQuery($sql);
$product_attributes = $sth->fetchAll();

$pageActive = "product_value_add";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('active_tab', '#product');

$smarty->assign("product_attributes", $product_attributes);
