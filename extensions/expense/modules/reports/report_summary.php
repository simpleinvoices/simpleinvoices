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


$sql="SELECT 
        e.amount AS expense, 
        e.status AS status, 
        ea.name AS account,
        (SELECT sum(tax_amount) FROM ".TB_PREFIX."expense_item_tax WHERE expense_id = e.id) AS tax,
        (SELECT tax + e.amount) AS total,
        (CASE WHEN status = 1 THEN '".$LANG['paid']."'
              WHEN status = 0 THEN '".$LANG['not_paid']."'
         END) AS status_wording
    FROM 
        ".TB_PREFIX."expense e, 
        ".TB_PREFIX."expense_account ea 
    WHERE 
        e.expense_account_id = ea.id 
        AND 
        e.date BETWEEN '$start_date' AND '$end_date'";
$sth = $db->query($sql);
$accounts = $sth->fetchAll();

$payment = new payment();
$payment->start_date = $start_date;
$payment->end_date = $end_date;
$payment->filter = "date";
$payments = $payment->select_all();


$invoice = new invoice();
$invoice->start_date = $start_date;
$invoice->end_date = $end_date;
$invoice->having = "date_between";
$invoice->sort = "preference";
$invoice_all = $invoice->select_all();

$invoices = $invoice_all->fetchAll();
$smarty -> assign('accounts', $accounts);
$smarty -> assign('payments', $payments);
$smarty -> assign('invoices', $invoices);
$smarty -> assign('start_date', $start_date);
$smarty -> assign('end_date', $end_date);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
