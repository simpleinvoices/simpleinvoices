<?php

/*
* Script: report_sales_by_period.php
* 	Sales reports by period add page
*
* Authors:
*	 Justin Kelly
*
* Last edited:
* 	 2008-05-13
*
* License:
*	 GPL v3
*
* Website:
* 	http://www.simpleinvoices.org
*/

checkLogin();

#$menu =false;

function firstOfMonth() {
	return date("Y-m-d", strtotime('01-01-'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
	return date("Y-m-d", strtotime('31-12-'.date('Y').' 00:00:00'));

}



isset($_POST['start_date']) ? $start_date = $_POST['start_date'] : $start_date = firstOfMonth() ;
isset($_POST['end_date']) ? $end_date = $_POST['end_date'] : $end_date = lastOfMonth() ;

isset($_POST['biller_id']) ? $biller_id = $_POST['biller_id'] : $biller_id = "" ;
isset($_POST['customer_id']) ? $customer_id = $_POST['customer_id'] : $customer_id = "" ;


if (isset($_POST['submit']))
{
	$invoice = new invoice();
	$invoice->start_date = $start_date;
	$invoice->end_date = $end_date;
	$invoice->biller = $biller_id;
	$invoice->customer = $customer_id;

	if ( isset($_POST['filter_by_date']) )
	{
		$invoice->having = "date_between";
	}

	if ( isset($_POST['show_only_unpaid']) )
	{
		$invoice->having_and = "money_owed";
		$show_only_unpaid = "yes";
	}

	$invoice->sort = "preference";
	$invoice_all = $invoice->select_all();

	$invoices = $invoice_all->fetchAll();
}

$billers = getActiveBillers();
$customers = getActiveCustomers();

$biller_details = getBiller($biller_id);
$customer_details = getCustomer($customer_id);
$smarty -> assign('biller_id', $biller_id);
$smarty -> assign('biller_details', $biller_details);
$smarty -> assign('customer_id', $customer_id);
$smarty -> assign('customer_details', $customer_details);

$smarty -> assign('show_only_unpaid', $show_only_unpaid);

$smarty -> assign('billers', $billers);
$smarty -> assign('customers', $customers);

$smarty -> assign('invoices', $invoices);
$smarty -> assign('start_date', $start_date);
$smarty -> assign('end_date', $end_date);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
$smarty -> assign('menu', $menu);
?>
