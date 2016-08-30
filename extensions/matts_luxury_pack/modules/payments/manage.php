<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/payments/manage.php
 * 	payment grid page
 *
 * Authors:
 *	yumatechnical@gmail.com
 *
 * Last edited:
 * 	2016-08-29
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
global $smarty;
// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
global $pagerows;//Matt
// TODO - replace get..Payments with simple count - as data is got by xml.php now
// @formatter:off
$query      = null;
$inv_id     = null;
$c_id       = null;
$preference = null;
$customer   = null;
// @formatter:on

if (!empty($_GET['id'])) {
    // Filter by just one invoice
    $inv_id        = $_GET['id'];
    $query         = getInvoicePayments($_GET['id']);
    $invoice       = getInvoice($_GET['id']);
    $preference    = getPreference($invoice['preference_id']);
    $subPageActive = "payment_filter_invoice";
} else if (!empty($_GET['c_id'])) {
    // Filter by just one customer
    $c_id          = $_GET['c_id'];
    $query         = getCustomerPayments($_GET['c_id']);
    $customer      = getCustomer($_GET['c_id']);
    $subPageActive = "payment_filter_customer";
} else {
    // No filters
    $query = getPayments();
    $subPageActive = "payment_manage";
}

$payments = progressPayments($query);

$smarty->assign("payments"  , $payments);
$smarty->assign("preference", $preference);
$smarty->assign("customer"  , $customer);

$smarty->assign("c_id"  , $c_id);
$smarty->assign("inv_id", $inv_id);

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive'   , 'payment');
$smarty->assign('active_tab'   , '#money');

$smarty->assign ("defaults", getSystemDefaults());//Matt
$smarty->assign ("array", $pagerows);//Matt
//$smarty -> assign("number_of_payment", npayment());//Matt
