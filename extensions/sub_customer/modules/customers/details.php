<?php
/*
 *  Script: details.php
 *      Customers details page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-07-27
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty, $pdoDb, $LANG, $config;

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
$sub_customers = SubCustomers::getSubCustomers($cid);

//TODO: Perhaps possible a bit nicer?
$stuff = array();
$stuff['total'] = Customer::calc_customer_total($customer['id']);
$stuff['paid' ] = Payment::calc_customer_paid($customer['id']);
$stuff['owing'] = $stuff['total'] - $stuff['paid'];

$customFieldLabel = getCustomFieldLabels('',true);
$invoices = Customer::getCustomerInvoices($cid);

$parent_customers = Customer::get_all(true);
$smarty->assign('parent_customers', $parent_customers);

$smarty->assign("stuff",$stuff);
$smarty->assign('customer',$customer);
$smarty->assign('sub_customers',$sub_customers);
$smarty->assign('invoices',$invoices);
$smarty->assign('customFieldLabel',$customFieldLabel);

$smarty->assign('pageActive', 'customer');

$subPageActive = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive', 'customer');

$smarty->assign('active_tab', '#people');
