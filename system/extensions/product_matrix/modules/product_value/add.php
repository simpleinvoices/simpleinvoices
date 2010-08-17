<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['value'] != "" ) {
	include("./extensions/product_matrix/modules/product_value/save.php");
}
$pageActive = "options";

$smarty->assign('pageActive', $pageActive);
$smarty -> assign('save',$save);

$sql = "select * from ".TB_PREFIX."products_attributes";
$sth =  dbQuery($sql);
$product_attributes = $sth->fetchAll();
$smarty -> assign("product_attributes", $product_attributes);

?>
