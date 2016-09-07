<?php

/*
 *  Script:
 *      Sales reports by period add page
 *
 *  Authors:
 *      Justin Kelly
 *
 *  Last edited:
 *      2016-08-15
 *
 *  License:
 *      GPL v3 or later
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
global $smarty, $pdoDb;

checkLogin ();

function firstOfMonth() {
    return date("Y-m-d", strtotime('01-01-' . date('Y') . ' 00:00:00'));
}
function lastOfMonth() {
    return date("Y-m-d", strtotime('31-12-' . date('Y') . ' 00:00:00'));
}

$start_date     = (isset($_POST['start_date']   )  ? $_POST['start_date']     : firstOfMonth());
$end_date       = (isset($_POST['end_date']     )  ? $_POST['end_date']       : lastOfMonth());
$custom_field3  = (isset($_POST['custom_field3'])  ? $_POST['custom_field3']  : "");
$filter_by_date = (isset($_POST['filter_by_date']) ? $_POST['filter_by_date'] : "no");

if (isset($_POST['submit'])) {
    $invoice = new invoice();
    $invoice->start_date  = $start_date;
    $invoice->end_date    = $end_date;
    $invoice->where_field = 'iv.custom_field3';
    $invoice->where_value = $custom_field3;

    if (isset($_POST['filter_by_date'])) {
        $invoice->having_and = "date_between";
        $filter_by_date      = "yes";
    }

    $invoice->sort = "date";
    $invoice_all = $invoice->select_all();

    $invoices = $invoice_all->fetchAll();

    $statement = array("total" => 0, "owing" => 0, "paid" => 0);
    foreach ( $invoices as $row ) {
        $statement['total'] += $row['invoice_total'];
        $statement['owing'] += $row['owing'];
        $statement['paid']  += $row['INV_PAID'];
    }
}

$pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
$pdoDb->addToWhere(new WhereItem(false, "custom_field3", "<>", "", false));
$pdoDb->addToFunctions("DISTINCT(custom_field3)", "custom_field3");
$cf3 = $pdoDb->request("SELECT", "invoices");

//$biller_details   = Biller::select($biller_id);
//$customer_details = Customer::get($customer_id);

//$smarty->assign('biller_id'       , $biller_id);
//$smarty->assign('biller_details'  , $biller_details);
//$smarty->assign('customer_id'     , $customer_id);
//$smarty->assign('customer_details', $customer_details);
$smarty->assign('cf3'             , $cf3);
$smarty->assign('custom_field3'   , $custom_field3);
$smarty->assign('filter_by_date'  , $filter_by_date);

$smarty->assign('invoices'  , $invoices);
$smarty->assign('statement' , $statement);
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date'  , $end_date);

$smarty->assign('pageActive', 'report' );
$smarty->assign('active_tab', '#home' );
$smarty->assign('menu'      , $menu );
