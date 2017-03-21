<?php
/*
 * Script: report_sales_by_period.php
 * Sales reports by period add page
 *
 * Authors:
 * Justin Kelly
 *
 * Last edited:
 *  2008-05-13
 *
 * License:
 * GPL v3
 *
 * Website:
 * http://www.simpleinvoices.org
 */
global $menu, $pdoDb, $smarty;

checkLogin ();
error_log("in invoice.php");
function firstOfMonth() {
    return date ( "Y-m-d", strtotime ( '01-01-' . date ( 'Y' ) . ' 00:00:00' ) );
}

function lastOfMonth() {
    return date ( "Y-m-d", strtotime ( '31-12-' . date ( 'Y' ) . ' 00:00:00' ) );
}

$start_date  = (isset($_POST['start_date'] ) ? $_POST['start_date']  : firstOfMonth());
$end_date    = (isset($_POST['end_date']   ) ? $_POST['end_date']    : lastOfMonth ());
$biller_id   = (isset($_POST['biller_id']  ) ? $_POST['biller_id']   : "");
$customer_id = (isset($_POST['customer_id']) ? $_POST['customer_id'] : "");

$show_only_unpaid      = "no";
$do_not_filter_by_date = "no";
$invoices              = array();
$statement             = array ("total" => 0, "owing" => 0, "paid" => 0);

if (isset($_POST['submit'])) {
    if (isset($_POST['do_not_filter_by_date'])) {
        $do_not_filter_by_date = "yes";
    } else {
        $do_not_filter_by_date = "no";
        $pdoDb->setHavings(Invoice::buildHavings("date_between", array($start_date, $end_date)));
    }

    if (isset($_POST['show_only_unpaid'])) {
        $show_only_unpaid = "yes";
        $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
    } else {
        $show_only_unpaid = "no";
    }

    if (!empty($biller_id)  ) $pdoDb->addSimpleWhere("biller_id"  , $biller_id  , "AND");
    if (!empty($customer_id)) $pdoDb->addSimpleWhere("customer_id", $customer_id, "AND");

    $invoices = Invoice::select_all("", "date", "D");
    foreach ( $invoices as $row ) {
        if ($row ['status'] > 0) {
            $statement ['total'] += $row ['invoice_total'];
            $statement ['owing'] += $row ['owing'];
            $statement ['paid']  += $row ['INV_PAID'];
        }
    }
}

// @formatter:off
$billers          = Biller::get_all(true);
$biller_count     = count($billers);
$customers        = Customer::get_all(true);
$customer_count   = count($customers);
$biller_details   = Biller::select($biller_id);
$customer_details = Customer::get($customer_id);

$smarty->assign('biller_id'       , $biller_id);
$smarty->assign('billers'         , $billers);
$smarty->assign('biller_count'    , $biller_count);
$smarty->assign('biller_details'  , $biller_details);
$smarty->assign('customer_id'     , $customer_id);
$smarty->assign('customers'       , $customers);
$smarty->assign('customer_count'  , $customer_count);
$smarty->assign('customer_details', $customer_details);

$smarty->assign('show_only_unpaid'     , $show_only_unpaid);
$smarty->assign('do_not_filter_by_date', $do_not_filter_by_date);

$smarty->assign('invoices'  , $invoices);
$smarty->assign('statement' , $statement);
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date'  , $end_date);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');

if (!isset($menu)) $menu = true; // Causes menu section of report gen page to display.
$smarty->assign('menu', $menu);
