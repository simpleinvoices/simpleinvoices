<?php

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pdoDb->addSimpleWhere("domain_id", domain_id::get());
$pdoDb->addToFunctions("count(*) AS count");
$number_of_rows = $pdoDb->request("SELECT", "products");

$defaults = getSystemDefaults();

$cflgs = getCustomFlagsQualified('E', domain_id::get());
$smarty->assign("cflgs", $cflgs);
$smarty->assign("defaults", $defaults);
$smarty->assign("number_of_rows", $number_of_rows);

$smarty->assign('pageActive', 'product_manage');
$smarty->assign('active_tab', '#product');
