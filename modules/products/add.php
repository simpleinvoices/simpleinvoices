<?php
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$customFieldLabel = getCustomFieldLabels('',true);
$taxes = Taxes::getActiveTaxes();

if (!empty($_POST['description'])) {
    include("modules/products/save.php");
}

$pdoDb->addSimpleWhere("enabled", ENABLED);
$attributes = $pdoDb->request("SELECT", "products_attributes");

$smarty->assign("defaults"        , getSystemDefaults());
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('taxes'           , $taxes);
$smarty->assign("attributes"      , $attributes);

$smarty->assign('pageActive'      , 'product_add');
$smarty->assign('active_tab'      , '#product');
// @formatter:on

