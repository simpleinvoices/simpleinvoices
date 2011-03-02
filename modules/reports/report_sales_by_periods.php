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


/*
* Get earliest invoice date
*/
$sql="select min(date) as date from ".TB_PREFIX."invoices";
$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
$invoice_start_array = $sth->fetch();

//$invoice_start = dbQuery($sql);
//$invoice_start_array= mysql_fetch_array($invoice_start);
$first_invoice_year = date('Y', strtotime( $invoice_start_array['date'] ) );
/*
* Get total for each month of each year from first invoice
*/
$this_year= date('Y');
$year = $first_invoice_year ;
//$years[]=$first_invoice_year ;
/*
* loop for each year
*/
while ( $year <= $this_year )
{	
	/*
	* loop for each month
	*/
	$month = 01;
	while ($month <= 12)
	{
		//make month nice for mysql - accounts table doesnt like it if not 08 etc..
		if ( $month < 10 )
		{ 
			$month="0".$month;
		};
		/*
		* Sales
		*/
		$total_month_sales_sql = "select sum(ii.total) as month_total from ".TB_PREFIX."invoice_items ii, si_invoices i, si_preferences p where i.id = ii.invoice_id AND i.preference_id = p.pref_id AND p.status = '1' AND i.date like '$year-$month%'";
		//$total_month_sales = mysqlQuery($total_month_sales_sql);
		//$total_month_sales_array= mysql_fetch_array($total_month_sales);

		$total_month_sales = dbQuery($total_month_sales_sql) or die(htmlsafe(end($dbh->errorInfo())));
		$total_month_sales_array = $total_month_sales -> fetch();

		$total_sales[$year][$month] = $total_month_sales_array['month_total'];
		if ($total_sales[$year][$month] == "" ) 
		{
			$total_sales[$year][$month] = "-";
		}

		/*
		* Payment
		*/
		$total_month_payments_sql = " select sum(ac_amount) as month_total_payments from ".TB_PREFIX."payment where ac_date like '$year-$month%'";
		//$total_month_payments = mysqlQuery($total_month_payments_sql);
		//$total_month_payments_array= mysql_fetch_array($total_month_payments);

		$total_month_payments = dbQuery($total_month_payments_sql) or die(htmlsafe(end($dbh->errorInfo())));
		$total_month_payments_array = $total_month_payments -> fetch();

		$total_payments[$year][$month] = $total_month_payments_array['month_total_payments'];
		if ($total_payments[$year][$month] == "" ) 
		{
			$total_payments[$year][$month] = "-";
		}
		$month++;
	}
	/*
	* Sales
	*/
	$total_year_sales_sql = "select sum(ii.total) as year_total from ".TB_PREFIX."invoice_items ii, si_invoices i, si_preferences p  where i.id = ii.invoice_id AND i.preference_id = p.pref_id AND p.status = '1' AND i.date like '$year%'";
	//$total_year_sales = mysqlQuery($total_year_sales_sql);
	//$total_year_sales_array= mysql_fetch_array($total_year_sales);

	$total_year_sales = dbQuery($total_year_sales_sql) or die(htmlsafe(end($dbh->errorInfo())));
	$total_year_sales_array = $total_year_sales -> fetch();

	$total_sales[$year]['Total'] = $total_year_sales_array['year_total'];
		if ($total_sales[$year]['Total']  == "" ) 
		{
			$total_sales[$year]['Total']  = "-";
		}

	/*
	* Payment
	*/
	$total_year_payments_sql = " select sum(ac_amount) as year_total_payments from ".TB_PREFIX."payment where ac_date like '$year%'";
	//$total_year_payments = mysqlQuery($total_year_payments_sql);
	//$total_year_payments_array= mysql_fetch_array($total_year_payments);

	$total_year_payments = dbQuery($total_year_payments_sql) or die(htmlsafe(end($dbh->errorInfo())));
	$total_year_payments_array = $total_year_payments -> fetch();

	$total_payments[$year]['Total'] = $total_year_payments_array['year_total_payments'];
		if ($total_payments[$year]['Total']  == "" ) 
		{
			$total_payments[$year]['Total']  = "-";
		}

	$years[]=$year ;
	$year++;
}
/*
echo "Total Sales<pre>";
print_r($total_sales);
echo "</pre><br />Total Payments<pre>";
print_r($total_payments);
echo "</pre>";
*/
$smarty->assign('total_sales', $total_sales);
$smarty->assign('total_payments', $total_payments);
//$years = array(2006,2007,2008);
$years = array_reverse($years);
$smarty->assign('years', $years);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
