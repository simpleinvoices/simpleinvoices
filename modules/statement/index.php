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
global $smarty, $menu;

checkLogin ();

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

$show_only_unpaid = "no";
$filter_by_date   = "no";
$invoices         = array();
$statement        = array ("total" => 0, "owing" => 0, "paid" => 0);


if (isset($_POST['submit'])) {
    $invoice = new Invoice();
    $invoice->start_date = $start_date;
    $invoice->end_date   = $end_date;
    $invoice->biller     = $biller_id;
    $invoice->customer   = $customer_id;
    $invoice->having     = "open";
    if (isset($_POST['filter_by_date'])) {
        $invoice->having  = "date_between";
        $filter_by_date   = "yes";
        $having_and_count = 1;
    }

    if (isset($_POST['show_only_unpaid'])) {
        if ($having_and_count == 1) {
            $invoice->having_and = "money_owed";
        } else {
            $invoice->having = "money_owed";
        }
        $show_only_unpaid = "yes";
    }

    $invoice->sort = "date";
    $invoice_all   = $invoice->select_all ();
    $invoices      = $invoice_all->fetchAll ();
    foreach ( $invoices as $row ) {
        if ($row ['status'] > 0) {
            $statement ['total'] += $row ['invoice_total'];
            $statement ['owing'] += $row ['owing'];
            $statement ['paid']  += $row ['INV_PAID'];
        }
    }
}

$billers   = Biller::get_all(true);
$customers = Customer::get_all(true);

$biller_details   = Biller::select($biller_id);
$customer_details = Customer::get($customer_id);

$smarty->assign('biller_id'       , $biller_id);
$smarty->assign('biller_details'  , $biller_details);
$smarty->assign('customer_id'     , $customer_id);
$smarty->assign('customer_details', $customer_details);

$smarty->assign('show_only_unpaid', $show_only_unpaid);
$smarty->assign('filter_by_date'  , $filter_by_date);

$smarty->assign('billers'   , $billers);
$smarty->assign('customers' , $customers);
$smarty->assign('invoices'  , $invoices);
$smarty->assign('statement' , $statement);
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date'  , $end_date);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');

if (!isset($menu)) $menu = true;
$smarty->assign('menu', $menu);
