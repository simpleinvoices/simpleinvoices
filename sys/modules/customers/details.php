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

$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

#get the invoice id
$customer_id = $_GET['id'];
$customer = customer::get($customer_id);
$customer['wording_for_enabled'] = $customer['enabled']==1?$LANG['enabled']:$LANG['disabled'];


//TODO: Perhaps possible a bit nicer?
$stuff = null;
$stuff['total'] = calc_customer_total($customer['id']);

#amount paid calc - start
$stuff['paid'] = calc_customer_paid($customer['id']);;
#amount paid calc - end

#amount owing calc - start
$stuff['owing'] = $stuff['total'] - $stuff['paid'];
#get custom field labels


$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();
$invoices = getCustomerInvoices($customer_id);

//$customFieldLabel = getCustomFieldLabels("biller");
$smarty -> assign("stuff",$stuff);
$smarty -> assign('customer',$customer);
$smarty -> assign('invoices',$invoices);
$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty -> assign('pageActive', 'customer');
$subPageActive = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('pageActive', 'customer');


$smarty -> assign('active_tab', '#people');
?>