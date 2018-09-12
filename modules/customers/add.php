<?php
/*
 * Script: add.php
 * 	    Customers add page
 *
 * Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 	    2016-07-27
 *
 * License:
 *	    GPL v3 or above
 *
 * Website:
 * 	    https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$customFieldLabel = getCustomFieldLabels('',true);

//if valid then do save
if (!empty($_POST['name'])) {
	include("modules/customers/save.php");
}
$smarty->assign('customFieldLabel',$customFieldLabel);
$smarty->assign('pageActive', 'customer');
$smarty->assign('subPageActive', 'customer_add');
$smarty->assign('active_tab', '#people');
