<?php

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$customFieldLabel = getCustomFieldLabels('',true);
$cflgs = getCustomFlagsQualified('E');
$taxes = getActiveTaxes();
// if valid then do save
if ($_POST['description'] != "") {
    // Use standard file for now.
    include ("./modules/products/save.php");
}
$smarty->assign("defaults", getSystemDefaults());
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('cflgs', $cflgs);
$smarty->assign('save', $save);
$smarty->assign('taxes', $taxes);

$sql = "select * from " . TB_PREFIX . "products_attributes where enabled ='1'";
$sth = dbQuery($sql);
$attributes = $sth->fetchAll();

$smarty->assign("attributes", $attributes);
$smarty->assign('pageActive', 'product_add');
$smarty->assign('active_tab', '#product');
