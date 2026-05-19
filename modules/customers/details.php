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

global $auth_session, $config;

#get the customer id
$customer_id = (int)$_GET['id']; // Cast to integer to prevent SQL injection
$customer = getCustomer($customer_id);
si_check_record_access($customer);
$customer['wording_for_enabled'] = $customer['enabled']==1?$LANG['enabled']:$LANG['disabled'];

$portalDomainId = (int) ($customer['domain_id'] ?? domain_id::get());
$siBase = rtrim(str_replace('\\', '', dirname($_SERVER['PHP_SELF'])), '/');
$portalDomainRow = dbQuery(
    "SELECT name FROM " . TB_PREFIX . "user_domain WHERE id = :id LIMIT 1",
    ':id', $portalDomainId
)->fetch(PDO::FETCH_ASSOC);
$portalDomainSlug  = $portalDomainRow ? (string) $portalDomainRow['name'] : '';
$customerPortalUrl = $siBase . '/index.php?module=auth&view=customer_login&domain=' . rawurlencode($portalDomainSlug);
$showCustomerPortalLink = ((int) ($config->authentication->enabled ?? 0)) === 1
    && ($auth_session->role_name ?? '') !== 'customer';
$bladeView->assign('customerPortalUrl', $customerPortalUrl);
$bladeView->assign('showCustomerPortalLink', $showCustomerPortalLink);


//TODO: Perhaps possible a bit nicer?
$stuff = null;
$stuff['total'] = calc_customer_total($customer['id'],domain_id::get(),true);

#amount paid calc - start
$stuff['paid'] = calc_customer_paid($customer['id'],domain_id::get(),true);;
#amount paid calc - end

#amount owing calc - start
$stuff['owing'] = $stuff['total'] - $stuff['paid'];
#get custom field labels


$customFieldLabel = getCustomFieldLabels();
$invoices = getCustomerInvoices($customer_id);

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir =  "DESC" ;
$sort =  "id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$having = 'money_owed' ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
$invoice_owing = new invoice();
$invoice_owing->sort=$sort;
$invoice_owing->having_and="real";
$invoice_owing->query=$_REQUEST['query'];
$invoice_owing->qtype=$_REQUEST['qtype'];

$large_dataset = getDefaultLargeDataset();
if($large_dataset == $LANG['enabled'])
{
  $sth = $invoice_owing->select_all('large_count', $dir, $rp, $page, $having);
} else {
  $sth = $invoice_owing->select_all('', $dir, $rp, $page, $having);

}
$invoices_owing = $sth->fetchAll(PDO::FETCH_ASSOC);

//$customFieldLabel = getCustomFieldLabels("biller");
$bladeView -> assign("stuff",$stuff);
$bladeView -> assign('customer',$customer);
$bladeView -> assign('invoices',$invoices);
$bladeView -> assign('invoices_owing',$invoices_owing);
$bladeView -> assign('customFieldLabel',$customFieldLabel);

$bladeView -> assign('pageActive', 'customer');
$subPageActive = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('pageActive', 'customer');


$bladeView -> assign('active_tab', '#people');
?>
