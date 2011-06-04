<?php
/*
* Script: add.php
* 	Customers add page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$customFieldLabel = getCustomFieldLabels();

//if valid then do save
if ($_POST['name'] != "" ) {
	include("./extensions/sub_customer/modules/customers/save.php");
}
$smarty -> assign('customFieldLabel',$customFieldLabel);

$parent_customers = getActiveCustomers();
$smarty -> assign('parent_customers', $parent_customers);
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('subPageActive', 'customer_add');
$smarty -> assign('active_tab', '#people');
?>
