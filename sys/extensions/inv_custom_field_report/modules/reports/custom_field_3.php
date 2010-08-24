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

isset($_POST['custom_field3']) ? $custom_field3 = $_POST['custom_field3'] : $custom_field3 = "" ;


if (isset($_POST['submit']))
{
	$invoice = new invoice2();
	$invoice->start_date = $start_date;
	$invoice->end_date = $end_date;
	$invoice->where_field = 'iv.custom_field3';
	$invoice->where_value = $custom_field3;

	if ( isset($_POST['filter_by_date']) )
	{
		$invoice->having_and = "date_between";
		$filter_by_date = "yes";
		$having_and_count = 1;
	}

	$invoice->sort = "date";
	$invoice_all = $invoice->select_all();

	$invoices = $invoice_all->fetchAll();

	foreach ($invoices as $i => $row) {
		$statement['total'] = $statement['total'] + $row['invoice_total'];
		$statement['owing'] = $statement['owing'] + $row['owing'] ;
		$statement['paid'] = $statement['paid'] + $row['INV_PAID'];
		
	}
}

$sql = "select DISTINCT(custom_field3) from  " . TB_PREFIX . "invoices where custom_field3 != ''";
$cf3 = $db->query($sql);

$biller_details = getBiller($biller_id);
$customer_details = getCustomer($customer_id);
$smarty -> assign('biller_id', $biller_id);
$smarty -> assign('biller_details', $biller_details);
$smarty -> assign('customer_id', $customer_id);
$smarty -> assign('customer_details', $customer_details);
$smarty -> assign('cf3', $cf3->fetchAll());
$smarty -> assign('custom_field3', $custom_field3);

$smarty -> assign('filter_by_date', $filter_by_date);


$smarty -> assign('invoices', $invoices);
$smarty -> assign('statement', $statement);
$smarty -> assign('start_date', $start_date);
$smarty -> assign('end_date', $end_date);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
$smarty -> assign('menu', $menu);
?>
