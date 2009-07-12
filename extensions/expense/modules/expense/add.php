<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$expense_add = expense::add();

$defaults = getSystemDefaults();

$taxes = getActiveTaxes();

//if valid then do save
if ($_POST['description'] != "" ) {
	include("./modules/products/save.php");
}

$smarty -> assign('save',$save);
$smarty -> assign('taxes',$taxes);
$smarty -> assign('expense_add',$expense_add);
$smarty -> assign('defaults',$defaults);

$smarty -> assign('pageActive', 'product_add');
$smarty -> assign('active_tab', '#product');
?>
