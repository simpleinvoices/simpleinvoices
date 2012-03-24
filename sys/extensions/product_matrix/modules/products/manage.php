<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PRODUCTS = new SimpleInvoices_Db_Table_Products();
$number_of_rows = $SI_PRODUCTS->getCount();

$smarty -> assign("number_of_rows",$number_of_rows);

$pageActive = "products";
$smarty->assign('pageActive', $pageActive);
?>
