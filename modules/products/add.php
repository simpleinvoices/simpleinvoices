<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#get custom field labels
$customFieldLabel = getCustomFieldLabels();
$taxes = getActiveTaxes();
//if valid then do save
if ($_POST['description'] != "" ) {
	include("./modules/products/save.php");
}
$bladeView -> assign("defaults",getSystemDefaults());
$bladeView -> assign('customFieldLabel',$customFieldLabel);
$bladeView -> assign('save',$save);
$bladeView -> assign('taxes',$taxes);

$sql = "select * from ".TB_PREFIX."products_attributes where enabled ='1'";
$sth =  dbQuery($sql);
$attributes = $sth->fetchAll();

$bladeView -> assign("attributes", $attributes);
$bladeView -> assign('pageActive', 'product_add');
$bladeView -> assign('active_tab', '#product');
?>
