<?php


if ($_POST['op'] =='edit' AND !empty($_POST['invoice_id']))
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

$invoice_all = invoice::get_all();

$get_cron = new cron();
$get_cron->id = $_GET['id'];
$cron = $get_cron->select();

$smarty -> assign('invoice_all',$invoice_all);
$smarty -> assign('saved',$saved);
$smarty -> assign('cron',$cron);
$smarty -> assign('pageActive', 'cron');
$smarty -> assign('subPageActive', 'cron_view');
$smarty -> assign('active_tab', '#money');