<?php

/*
* Script: report_sales_by_period.php
* 	Sales reports by period add page
*
* Authors:
*	 Justin Kelly
*
* Last edited:
* 	 2016-08-16
*
* License:
*	 GPL v3
*
* Website:
* 	https://simpleinvoices.group
*/
global $LANG, $smarty, $pdoDb;

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

$pdoDb->setSelectList(array("e.amount AS expense", "e.status AS status", "ea.name AS account"));

$fn = new FunctionStmt("SUM", "tax_amount");
$fr = new FromStmt("expense_item_tax");
$wh = new WhereItem(false, "expense_id", "=", new DbField("e.id"), false);
$se = new Select($fn, $fr, $wh, "tax");
$pdoDb->addToSelectStmts($se);

$fn = new FunctionStmt("", "tax + e.amount");
$se = new Select($fn, null, null, "total");
$pdoDb->addToSelectStmts($se);

$ca = new CaseStmt(status, "status_wording");
$ca->addWhen("=", ENABLED, $LANG['paid']);
$ca->addWhen("<>", ENABLED, $LANG['not_paid'],true);
$pdoDb->addToCaseStmts($ca);

$jn = new Join("LEFT", "expense_account", "ea");
$jn->addSimpleItem("e.expense_account_id", new DbField("ea.id"), "AND");
$jn->addSimpleItem("e.domain_id", new DbField("ea.domain_id"));
$pdoDb->addToJoins($jn);

$pdoDb->addSimpleWhere("e.domain_id", $domain_id, "AND");
$pdoDb->addToWhere(new WhereItem(false, "e.date", "BETWEEN", array($start_date, $end_date), false));

$accounts = $pdoDb->request("SELECT", "expense", "e");
/*
$sql="SELECT e.amount AS expense, e.status AS status, ea.name AS account,
             (SELECT sum(tax_amount) FROM ".TB_PREFIX."expense_item_tax WHERE expense_id = e.id) AS tax,
             (SELECT tax + e.amount) AS total,
             (CASE WHEN status = 1 THEN '".$LANG['paid']."' WHEN status = 0 THEN '".$LANG['not_paid']."' END) AS status_wording
      FROM ".TB_PREFIX."expense e
      LEFT JOIN ".TB_PREFIX."expense_account ea ON (e.expense_account_id = ea.id AND e.domain_id = ea.domain_id)
      WHERE e.domain_id = :domain_id AND e.date BETWEEN '$start_date' AND '$end_date'";
$sth = $db->query($sql, ':domain_id', $domain_id);
$accounts = $sth->fetchAll(PDO::FETCH_ASSOC);
*/
// @formatter:off
$payments = Payment::select_by_date($start_date, $end_date, "date", "");

$pdoDb->setHavings(Invoice::buildHavings("date_between", array($start_date, $end_date)));
$invoices = Invoice::select_all("", "preference", "A", null, "", "", "");

$smarty->assign('accounts'  , $accounts);
$smarty->assign('payments'  , $payments);
$smarty->assign('invoices'  , $invoices);
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date'  , $end_date);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
// @formatter:on
