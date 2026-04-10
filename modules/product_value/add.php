<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['value'] !== '' ) {
	include("./modules/product_value/save.php");
}

$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
$sth =  dbQuery($sql);
$product_attributes = $sth->fetchAll();

$pageActive = "product_value_add";
$bladeView->assign('pageActive', $pageActive);
$bladeView -> assign('active_tab', '#product');

$bladeView -> assign("product_attributes", $product_attributes);
$bladeView -> assign('save',$save);

?>
