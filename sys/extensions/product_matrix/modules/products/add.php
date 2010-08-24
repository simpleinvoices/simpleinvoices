<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#get custom field labels
$customFieldLabel = getCustomFieldLabels();

//if valid then do save
if ($_POST['description'] != "" ) {
	include("./extensions/product_matrix/modules/products/save.php");
}
$pageActive = "products";

$smarty->assign('pageActive', $pageActive);
$smarty -> assign('customFieldLabel',$customFieldLabel);
$smarty -> assign('save',$save);


$sql = "select * from ".TB_PREFIX."products_attributes";
$sth =  dbQuery($sql);
$attributes = $sth->fetchAll();
$smarty -> assign("attributes", $attributes);
?>
