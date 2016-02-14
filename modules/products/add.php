<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$customFieldLabel = getCustomFieldLabels('',true);
$taxes = getActiveTaxes();

if ($_POST['description'] != "" ) {
    include("./modules/products/save.php");
}

$sth = dbQuery("select * from ".TB_PREFIX."products_attributes where enabled ='1'");
$attributes = $sth->fetchAll();

$smarty->assign("defaults"        , getSystemDefaults());
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('save'            , $save);
$smarty->assign('taxes'           , $taxes);
$smarty->assign("attributes"      , $attributes);
$smarty->assign('pageActive'      , 'product_add');
$smarty->assign('active_tab'      , '#product');
// @formatter:on
?>
