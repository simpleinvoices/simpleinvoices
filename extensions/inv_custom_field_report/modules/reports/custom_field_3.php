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
 *      https://simpleinvoices.group
 */
global $menu, $pdoDb, $smarty;

checkLogin ();

function firstOfMonth() {
    return date("Y-m-d", strtotime('01-01-' . date('Y') . ' 00:00:00'));
}
function lastOfMonth() {
    return date("Y-m-d", strtotime('31-12-' . date('Y') . ' 00:00:00'));
}

// @formatter:off
$start_date     = (isset($_POST['start_date']   )  ? $_POST['start_date']     : firstOfMonth());
$end_date       = (isset($_POST['end_date']     )  ? $_POST['end_date']       : lastOfMonth());
$custom_field3  = (isset($_POST['custom_field3'])  ? $_POST['custom_field3']  : "");
$filter_by_date = (isset($_POST['filter_by_date']) ? $_POST['filter_by_date'] : "no");

if (isset($_POST['submit'])) {
    $pdoDb->addSimpleWhere("iv.custom_field3", $custom_field3);
    if (isset($_POST['filter_by_date'])) {
        $pdoDb->setHaving(Invoice::buildHavings("date_between", array($start_date, $end_date)));
        $filter_by_date = "yes";
    }

    $invoices = Invoice::select_all("", "date", "", null, "", "", "", "", "");

    $statement = array("total" => 0, "owing" => 0, "paid" => 0);
    foreach ( $invoices as $row ) {
        $statement['total'] += $row['invoice_total'];
        $statement['owing'] += $row['owing'];
        $statement['paid' ] += $row['INV_PAID'];
    }
}

$pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
$pdoDb->addToWhere(new WhereItem(false, "custom_field3", "<>", "", false));
$pdoDb->addToFunctions(new FunctionStmt("DISTINCT", "custom_field3", "custom_field3"));
$cf3 = $pdoDb->request("SELECT", "invoices");

$smarty->assign('cf3'           , $cf3);
$smarty->assign('custom_field3' , $custom_field3);
$smarty->assign('filter_by_date', $filter_by_date);
$smarty->assign('invoices'      , $invoices);
$smarty->assign('statement'     , $statement);
$smarty->assign('start_date'    , $start_date);
$smarty->assign('end_date'      , $end_date);

$smarty->assign('pageActive', 'report' );
$smarty->assign('active_tab', '#home' );
$smarty->assign('menu'      , $menu );
// @formatter:on
