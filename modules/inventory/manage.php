<?php
/*
 *  Script: manage.php
 *      Manage Invoices page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$number_of_rows = Inventory::count();
$smarty->assign("number_of_rows",$number_of_rows);

$smarty->assign('pageActive', 'inventory');
$smarty->assign('active_tab', '#product');

$url = 'index.php?module=inventory&view=xml';
$smarty->assign('url', $url);
