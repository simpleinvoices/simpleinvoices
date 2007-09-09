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


//TODO: Perhaps possible a bit nicer?
$stuff = null;
$stuff['total'] = calc_customer_total($customer['id']);
$stuff['total_format'] = number_format($stuff['total'],2);

#amount paid calc - start
$stuff['paid'] = calc_customer_paid($customer['id']);;
$stuff['paid_format'] = number_format($stuff['paid'],2);
#amount paid calc - end

#amount owing calc - start
$stuff['owing'] = number_format($stuff['total'] - $stuff['paid'],2);
#get custom field labels



$customFieldLabel = getCustomFieldLabels();
$invoices = getCustomerInvoices($customer_id);




//$customFieldLabel = getCustomFieldLabels("biller");
$pageActive = "customers";
$smarty->assign('pageActive', $pageActive);

$smarty -> assign("stuff",$stuff);
$smarty -> assign('customer',$customer);
$smarty -> assign('invoices',$invoices);
$smarty -> assign('customFieldLabel',$customFieldLabel);

?>
