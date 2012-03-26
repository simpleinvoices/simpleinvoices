<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

#get custom field labels
$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();

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
