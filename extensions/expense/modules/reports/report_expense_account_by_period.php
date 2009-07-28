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


$sql="select e.amount as expense, ea.name as account from si_expense e, si_expense_account ea where e.expense_account_id = ea.id and e.date between '$start_date' and '$end_date' group by account order by account asc;";
$sth = $db->query($sql);
$accounts = $sth->fetchAll();

$smarty -> assign('accounts', $accounts);
$smarty -> assign('start_date', $start_date);
$smarty -> assign('end_date', $end_date);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
