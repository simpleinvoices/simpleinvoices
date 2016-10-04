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

$domain_id = domain_id::get();

function firstOfMonth() {
	return date("Y-m-d", strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
	return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'))));
}

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : firstOfMonth() ;
$end_date   = isset($_POST['end_date'])   ? $_POST['end_date']   : lastOfMonth()  ;


$sql="SELECT e.amount AS expense
           , ea.name AS account 
	FROM ".TB_PREFIX."expense e 
		 LEFT JOIN ".TB_PREFIX."expense_account ea 
			ON (e.expense_account_id = ea.id AND e.domain_id = ea.domain_id)
	WHERE
	    e.domain_id = :domain_id
	AND e.date BETWEEN '$start_date' AND '$end_date' 
	GROUP BY account 
	ORDER BY account ASC;";
$sth = $db->query($sql, ':domain_id', $domain_id);
$accounts = $sth->fetchAll();

$smarty -> assign('accounts', $accounts);
$smarty -> assign('start_date', $start_date);
$smarty -> assign('end_date', $end_date);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
