<?php
/*
* Script: details.php
* 	Customers details page
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

if (!isset($_GET['id'])) {
	throw new SimpleInvoices_Exception('Invalid customer identifier.');
}

$customer = new SimpleInvoices_Customer($_GET['id']);
$stuff['total'] = $customer->getTotal();
$stuff['paid'] = $customer->getPaidAmount();
$stuff['owing'] = $stuff['total'] - $stuff['paid'];

#get custom field labels
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();
$invoices = getCustomerInvoices($customer_id);

$customerData = $customer->toArray();
$customerData['wording_for_enabled'] = $customerData['enabled']==1?$LANG['enabled']:$LANG['disabled'];

$smarty -> assign("stuff",$stuff);
$smarty -> assign('customer',$customerData);
$smarty -> assign('invoices',$invoices);
$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty -> assign('pageActive', 'customer');
$subPageActive = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('pageActive', 'customer');


$smarty -> assign('active_tab', '#people');
?>