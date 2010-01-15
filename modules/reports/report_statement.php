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

function firstOfMonth() {
return date("Y-m-d", strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'))));
}



isset($_POST['start_date']) ? $start_date = $_POST['start_date'] : $start_date = firstOfMonth() ;
isset($_POST['end_date']) ? $end_date = $_POST['end_date'] : $end_date = lastOfMonth() ;

isset($_POST['biller_id']) ? $biller = $_POST['biller_id'] : $biller = "" ;
isset($_POST['customer_id']) ? $customer = $_POST['customer_id'] : $customer = "" ;


$invoice = new invoice();
$invoice->start_date = $start_date;
$invoice->end_date = $end_date;
$invoice->biller = $biller;
$invoice->customer = $customer;
$invoice->having = "date_between";

if ( isset($_POST['only_unpaid_invoices']) )
{
	$invoice->having_and = "money_owed";
	$only_unpaid_invoices = "yes";
}

$invoice->sort = "preference";
$invoice_all = $invoice->select_all();

$invoices = $invoice_all->fetchAll();


$billers = getActiveBillers();
$customers = getActiveCustomers();

$smarty -> assign('biller', $biller);
$smarty -> assign('customer', $customer);

$smarty -> assign('only_unpaid_invoices', $only_unpaid_invoices);

$smarty -> assign('billers', $billers);
$smarty -> assign('customers', $customers);

$smarty -> assign('invoices', $invoices);
$smarty -> assign('start_date', $start_date);
$smarty -> assign('end_date', $end_date);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
