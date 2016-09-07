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
global $smarty, $LANG, $pdoDb, $config;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$cid = $_GET['id'];
$domain_id = domain_id::get();

$pdoDb->addSimpleWhere("id", $cid, "AND");
$pdoDb->addSimpleWhere("domain_id", $domain_id);
$rows = $pdoDb->request("SELECT", "customers");
$customer = $rows[0];
$customer['wording_for_enabled'] = ($customer['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
if (empty($customer['credit_card_number'])) {
    $customer['credit_card_number_masked'] = "";
} else {
    try {
        $key = $config->encryption->default->key;
        $enc = new Encryption();
        $credit_card_number = $enc->decrypt($key, $customer['credit_card_number']);
        $customer['credit_card_number_masked'] = maskValue($credit_card_number);
    } catch (Exception $e) {
        throw new Exception("details.php - Unable to decrypt credit card for Customer, " .
                            $cid . ". " . $e->getMessage());
    }
}
$invoices = Customer::getCustomerInvoices($cid);

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
$invoice_owing->query      = (isset($_REQUEST['query']) ? $_REQUEST['query'] : "");
$invoice_owing->qtype      = (isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : "");

$large_dataset = getDefaultLargeDataset();
if($large_dataset == $LANG['enabled']) {
  $sth = $invoice_owing->select_all('large_count', $dir, $rp, $page, $having);
} else {
  $sth = $invoice_owing->select_all('', $dir, $rp, $page, $having);
}

$invoices_owing = $sth->fetchAll(PDO::FETCH_ASSOC);
$subPageActive  = ($_GET['action'] == "view"  ? "customer_view" : "customer_edit");

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
