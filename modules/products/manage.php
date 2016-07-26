<?php
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$product = new product();
$count = $product->count();

$defaults = getSystemDefaults();
$smarty->assign("defaults",$defaults);
$smarty->assign("number_of_rows",$count);

$smarty->assign('pageActive', 'product_manage');
$smarty->assign('active_tab', '#product');
