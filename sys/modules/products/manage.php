<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_PRODUCTS = new SimpleInvoices_Db_Table_Products();
$number_of_rows = $SI_PRODUCTS->getCount();

$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
$smarty -> assign("defaults",$defaults);
$smarty -> assign("number_of_rows",$number_of_rows);

$smarty -> assign('pageActive', 'product_manage');
$smarty -> assign('active_tab', '#product');
