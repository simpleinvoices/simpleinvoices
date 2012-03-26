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

$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();

//if valid then do save
if (isset($_POST['name']) && $_POST['name'] != "" ) {
	include("sys/modules/customers/save.php");
}
$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty -> assign('pageActive', 'customer');
$smarty -> assign('subPageActive', 'customer_add');
$smarty -> assign('active_tab', '#people');
?>