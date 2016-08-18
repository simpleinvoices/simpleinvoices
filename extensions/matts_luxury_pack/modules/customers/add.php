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
if (!isset($_POST['name']) || $_POST['name'] != "" ) {
	include("./modules/customers/save.php");
}
$smarty -> assign('customFieldLabel',$customFieldLabel);
$smarty -> assign('number_of_customers', ncustomers()['count']);
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('subPageActive', 'customer_add');
$smarty -> assign('active_tab', '#people');
?>
