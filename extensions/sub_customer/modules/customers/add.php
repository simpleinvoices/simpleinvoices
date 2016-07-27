<?php
/*
 * Script: add.php
 * 	Customers add page
 *
 * Authors:
 *	 Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 	 2016-07-27
 *
 * License:
 *	 GPL v3 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$customFieldLabel = getCustomFieldLabels('',true);

//if valid then do save
if (!empty($_POST['name'])) {
	include("./extensions/sub_customer/modules/customers/save.php");
}
$smarty->assign('customFieldLabel',$customFieldLabel);

$parent_customers = getActiveCustomers();
$smarty->assign('parent_customers', $parent_customers);
$smarty->assign('pageActive'      , 'customer');
$smarty->assign('subPageActive'   , 'customer_add');
$smarty->assign('active_tab'      , '#people');
