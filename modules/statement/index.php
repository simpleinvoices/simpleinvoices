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
	$invoice->having = "open";

	if ( isset($_POST['filter_by_date']) )
	{
		$invoice->having = "date_between";
		$filter_by_date = "yes";
		$having_and_count = 1;
	}

	if ( isset($_POST['show_only_unpaid']) )
	{
		if ($having_and_count == 1) 
		{
			$invoice->having_and = "money_owed";
		} else {
			$invoice->having = "money_owed";
		}
		$show_only_unpaid = "yes";
	}

	$invoice->sort = "date";
	$invoice_all = $invoice->select_all();

	$invoices = $invoice_all->fetchAll();

	foreach ($invoices as $i => $row) {
		if ($row['status'] > 0) {
			$statement['total'] = $statement['total'] + $row['invoice_total'];
			$statement['owing'] = $statement['owing'] + $row['owing'] ;
			$statement['paid']  = $statement['paid']  + $row['INV_PAID'];
		}
	}
}

$billers = getActiveBillers();
$customers = getActiveCustomers();

$biller_details = $biller_id ? getBiller((int)$biller_id) : [];
$customer_details = $customer_id ? getCustomer((int)$customer_id) : [];
if ($biller_id && empty($biller_details)) si_check_record_access(false);
if ($customer_id && empty($customer_details)) si_check_record_access(false);
$bladeView -> assign('biller_id', $biller_id);
$bladeView -> assign('biller_details', $biller_details);
$bladeView -> assign('customer_id', $customer_id);
$bladeView -> assign('customer_details', $customer_details);

$bladeView -> assign('show_only_unpaid', $show_only_unpaid);
$bladeView -> assign('filter_by_date', $filter_by_date);

$bladeView -> assign('billers', $billers);
$bladeView -> assign('customers', $customers);

$bladeView -> assign('invoices', $invoices);
$bladeView -> assign('statement', $statement);
$bladeView -> assign('start_date', $start_date);
$bladeView -> assign('end_date', $end_date);

$bladeView -> assign('pageActive', 'report');
$bladeView -> assign('active_tab', '#home');
$bladeView -> assign('menu', $menu);
?>
