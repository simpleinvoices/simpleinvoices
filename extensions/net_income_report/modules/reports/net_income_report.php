<?php
/*
 * Script: net_income_wo_non_income.php Report income excluding non-income funds Authors: Richard
 * Rowley
 * Last edited: 2015-09-14
 * License: GPL v3
 * Website: http://www.simpleinvoices.org
 */
checkLogin();

function firstOfMonth() {
    return date('Y-m-d', strToTime('first day of this month'));
}

function lastOfMonth() {
    return date('Y-m-d', strToTime('last day of this month'));
}

// @formatter:off
$start_date  = isset($_POST['start_date'])  ? $_POST['start_date']  : firstOfMonth();
$end_date    = isset($_POST['end_date'])    ? $_POST['end_date']    : lastOfMonth();
$custom_flag = isset($_POST['custom_flag']) ? $_POST['custom_flag'] : null;

$custom_flags_enabled = isExtensionEnabled('custom_flags');
$custom_flag_labels   = getCustomFlagLabels($custom_flags_enabled);
// @formatter:on

$items = array();
$tot_income = 0;
if (isset($_POST['submit'])) {
    $neti = new NetIncomeReport();
    $invoices = $neti->select_rpt_items($start_date , $end_date, $custom_flag);

    foreach ($invoices as $invoice) {
        $tot_income += $invoice->total_period_payments;
    }
}

$smarty->assign('invoices', $invoices);
$smarty->assign('tot_income', $tot_income);
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date', $end_date);

$smarty->assign('custom_flags_enabled', ($custom_flags_enabled ? '1':'0'));
$smarty->assign('custom_flag', $custom_flag);
$smarty->assign('custom_flag_labels', $custom_flag_labels);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
$smarty->assign('menu', $menu);
