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

#amount paid calc - start
$stuff['paid'] = calc_customer_paid($customer['id']);;
#amount paid calc - end

#amount owing calc - start
$stuff['owing'] = $stuff['total'] - $stuff['paid'];
#get custom field labels



$customFieldLabel = getCustomFieldLabels();

$sSQL = "SELECT	iv.id, iv.date, iv.type_id, 
	@invd:=(SELECT sum( IF(isnull(ivt.total), 0, ivt.total)) 
		FROM " . TB_PREFIX . "invoice_items ivt where ivt.invoice_id = iv.id) As invd, 
	@apmt:=(SELECT sum( IF(isnull(ap.ac_amount), 0, ap.ac_amount)) 
		FROM " . TB_PREFIX . "account_payments ap where ap.ac_inv_id = iv.id) As pmt, 
	IF(isnull(@invd), 0, @invd) As total, 
	IF(isnull(@apmt), 0, @apmt) As paid, 
	(select (total - paid)) as owing 
FROM " . TB_PREFIX . "invoices iv 
WHERE iv.customer_id = $customer_id 
ORDER BY iv.id DESC";

$invoices = sql2array($sSQL);


//$customFieldLabel = getCustomFieldLabels("biller");
$pageActive = "customers";
$smarty->assign('pageActive', $pageActive);

$smarty -> assign("stuff",$stuff);
$smarty -> assign('customer',$customer);
$smarty -> assign('invoices',$invoices);
$smarty -> assign('customFieldLabel',$customFieldLabel);

?>
