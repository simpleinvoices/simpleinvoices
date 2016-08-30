<?php
/*
* Script: ./extensions/matts_luxury_pack/modules/customers/add.php
* 	Customers add page
*
* Authors:
*	 yumatechnical@gmail.com
*
* Last edited:
* 	 2016-08-29
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
global $cc_months, $cc_years;//Matt

//if valid then do save
if (!isset($_POST['name']) || $_POST['name'] != "" ) {
	include("./modules/customers/save.php");
}
$smarty -> assign('customFieldLabel',$customFieldLabel);
$smarty -> assign('number_of_customers', ncustomers()['count']);//Matt
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('subPageActive', 'customer_add');
$smarty -> assign('active_tab', '#people');
$smarty->assign('cc_months', $cc_months);//Matt
$smarty->assign('cc_years', $cc_years);//Matt
