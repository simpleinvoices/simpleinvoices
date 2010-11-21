<?php


if ($_POST['op'] =='add' AND !empty($_POST['invoice_id']))
{
	$cron = new cron();
	$cron->domain_id=domain_id::get();
	$cron->invoice_id=$_POST['invoice_id'];
	$cron->start_date=$_POST['start_date'];
	$cron->end_date=$_POST['end_date'];
	$cron->recurrence=$_POST['recurrence'];
	$cron->recurrence_type=$_POST['recurrence_type'];
	$cron->email_biller=$_POST['email_biller'];
	$cron->email_customer=$_POST['email_customer'];
	$result = $cron->insert();

	$saved = !empty($result) ? "true" : "false";
}      

$invoices = new invoice();
$invoices->sort='id';
$invoice_all = $invoices->select_all('count');

$smarty -> assign('invoice_all',$invoice_all);
$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'cron');
$smarty -> assign('subPageActive', 'cron_add');
$smarty -> assign('active_tab', '#money');
