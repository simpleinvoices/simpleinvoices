<?php

/*
* Script: report_sales_by_period.php
* 	Sales reports by period add page
*
* Authors:
*	 Justin Kelly
*	Francois Dechery, aka Soif
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

$max_years=10;

// Get earliest invoice date
$sql="select min(date) as date from ".TB_PREFIX."invoices";
$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
$invoice_start_array = $sth->fetch();
$first_invoice_year = date('Y', strtotime( $invoice_start_array['date'] ) );

//Get total for each month of each year from first invoice
$this_year= date('Y');
$year = $first_invoice_year ;

$total_years= $this_year - $first_invoice_year +1;
if( $total_years > $max_years){
	$year= $this_year - $max_years +1;
}

function _myRate($this_year_amount, $last_year_amount,$precision=2){
	if(!$last_year_amount){return '';}
	$rate=round( ($this_year_amount - $last_year_amount) / $last_year_amount * 100 , $precision);
	return $rate;
}

//loop for each year

$years=array();
$data=array();

while ( $year <= $this_year ){

	// loop for each month
	$month = 01;

	while ($month <= 12){
		//make month nice for mysql - accounts table doesnt like it if not 08 etc..
		if ( $month < 10 ){ 
			$month="0".$month;
		};
		
		// Sales ----------------------------
		$total_month_sales_sql = "select sum(ii.total) as month_total from ".TB_PREFIX."invoice_items ii, si_invoices i, si_preferences p where i.id = ii.invoice_id AND i.preference_id = p.pref_id AND p.status = '1' AND i.date like '$year-$month%'";
		$total_month_sales = dbQuery($total_month_sales_sql) or die(htmlsafe(end($dbh->errorInfo())));
		$total_month_sales_array = $total_month_sales -> fetch();

		$data['sales']['months'][$month][$year] = $total_month_sales_array['month_total'];
		$data['sales']['months_rate'][$month][$year] = _myRate($data['sales']['months'][$month][$year],	$data['sales']['months'][$month][$year -1]);
		

		// Payment ----------------------------
		$total_month_payments_sql = " select sum(ac_amount) as month_total_payments from ".TB_PREFIX."payment where ac_date like '$year-$month%'";
		$total_month_payments = dbQuery($total_month_payments_sql) or die(htmlsafe(end($dbh->errorInfo())));
		$total_month_payments_array = $total_month_payments -> fetch();

		$data['payments']['months'][$month][$year] 		= $total_month_payments_array['month_total_payments'];
		$data['payments']['months_rate'][$month][$year] = _myRate($data['payments']['months'][$month][$year],	$data['payments']['months'][$month][$year -1]);

		$month++;
	}

	// Total Sales ----------------------------
	$total_year_sales_sql = "select sum(ii.total) as year_total from ".TB_PREFIX."invoice_items ii, si_invoices i, si_preferences p  where i.id = ii.invoice_id AND i.preference_id = p.pref_id AND p.status = '1' AND i.date like '$year%'";
	$total_year_sales = dbQuery($total_year_sales_sql) or die(htmlsafe(end($dbh->errorInfo())));
	$total_year_sales_array = $total_year_sales -> fetch();

	$data['sales']['total'][$year] = $total_year_sales_array['year_total'];
	$data['sales']['total_rate'][$year] = _myRate($data['sales']['total'][$year],	$data['sales']['total'][$year -1]);

	// Total Payment ----------------------------
	$total_year_payments_sql = " select sum(ac_amount) as year_total_payments from ".TB_PREFIX."payment where ac_date like '$year%'";
	$total_year_payments = dbQuery($total_year_payments_sql) or die(htmlsafe(end($dbh->errorInfo())));
	$total_year_payments_array = $total_year_payments -> fetch();

	$data['payments']['total'][$year] 			= $total_year_payments_array['year_total_payments'];
	$data['payments']['total_rate'][$year]		= _myRate($data['payments']['total'][$year],	$data['payments']['total'][$year -1]);

	$years[]=$year ;
	$year++;
}

$smarty->assign('data',		$data);
$smarty->assign('all_years',$years);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
