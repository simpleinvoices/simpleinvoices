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

$domain_id = domain_id::get();
$sql = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE enabled = '1' AND domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', $domain_id);
$attributes = $sth->fetchAll();

$bladeView -> assign("attributes", $attributes);
$bladeView -> assign('pageActive', 'product_add');
$bladeView -> assign('active_tab', '#product');
?>
