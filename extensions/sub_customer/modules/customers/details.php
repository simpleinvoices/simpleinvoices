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


#get the invoice id
$customer_id = $_GET['id'];
$customer = getCustomer($customer_id);
$customer['wording_for_enabled'] = $customer['enabled']==1?$LANG['enabled']:$LANG['disabled'];

$sub_customers = getSubCustomers($customer_id);

//TODO: Perhaps possible a bit nicer?
$stuff = null;
$stuff['total'] = calc_customer_total($customer['id']);

#amount paid calc - start
$stuff['paid'] = calc_customer_paid($customer['id']);;
#amount paid calc - end

#amount owing calc - start
$stuff['owing'] = $stuff['total'] - $stuff['paid'];
#get custom field labels


$customFieldLabel = getCustomFieldLabels('',true);
$invoices = getCustomerInvoices($customer_id);

$parent_customers = getActiveCustomers();
$smarty -> assign('parent_customers', $parent_customers);
//$customFieldLabel = getCustomFieldLabels("biller");
$smarty -> assign("stuff",$stuff);
$smarty -> assign('customer',$customer);
$smarty -> assign('sub_customers',$sub_customers);
$smarty -> assign('invoices',$invoices);
$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty -> assign('pageActive', 'customer');
$subPageActive = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('pageActive', 'customer');


$smarty -> assign('active_tab', '#people');
?>
