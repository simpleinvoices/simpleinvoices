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

// @formatter:off
$customer_id = $_GET['id'];
$customer    = getCustomer($customer_id);
$customer['wording_for_enabled'] = $customer['enabled']==1?$LANG['enabled']:$LANG['disabled'];

$invoices = getCustomerInvoices($customer_id);

$stuff = array();
$stuff['total'] = calc_customer_total($customer['id'],domain_id::get(),true);
$stuff['paid']  = calc_customer_paid( $customer['id'],domain_id::get(),true);
$stuff['owing'] = $stuff['total'] - $stuff['paid'];

$customFieldLabel = getCustomFieldLabels('',true);

$dir    =  "DESC";
$sort   =  "id";
$having = "money_owed";
$rp     = (isset($_POST['rp'])   ? $_POST['rp']   : "25");
$page   = (isset($_POST['page']) ? $_POST['page'] : "1");

$invoice_owing = new invoice();
$invoice_owing->sort       = $sort;
$invoice_owing->having_and = "real";
$invoice_owing->query      = $_REQUEST['query'];
$invoice_owing->qtype      = $_REQUEST['qtype'];

$large_dataset = getDefaultLargeDataset();
if($large_dataset == $LANG['enabled']) {
  $sth = $invoice_owing->select_all('large_count', $dir, $rp, $page, $having);
} else {
  $sth = $invoice_owing->select_all('', $dir, $rp, $page, $having);
}

$invoices_owing = $sth->fetchAll(PDO::FETCH_ASSOC);
$subPageActive  = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;

$smarty->assign("stuff"           , $stuff);
$smarty->assign('customer'        , $customer);
$smarty->assign('invoices'        , $invoices);
$smarty->assign('invoices_owing'  , $invoices_owing);
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('pageActive'      , 'customer');
$smarty->assign('subPageActive'   , $subPageActive);
$smarty->assign('pageActive'      , 'customer');
$smarty->assign('active_tab'      , '#people');
// @formatter:on
?>
