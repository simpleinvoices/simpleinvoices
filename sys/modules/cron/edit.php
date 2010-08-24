<?php


if ($_POST['op'] =='edit' AND !empty($_POST['invoice_id']))
{
	$edit = new cron();
	$edit->domain_id=domain_id::get();
	$edit->id=$_GET['id'];
	$edit->invoice_id=$_POST['invoice_id'];
	$edit->start_date=$_POST['start_date'];
	$edit->end_date=$_POST['end_date'];
	$edit->recurrence=$_POST['recurrence'];
	$edit->recurrence_type=$_POST['recurrence_type'];
	$edit->email_biller=$_POST['email_biller'];
	$edit->email_customer=$_POST['email_customer'];
	$result = $edit->update();

	$saved = !empty($result) ? "true" : "false";
}      

$invoices = new invoice();
$invoices->sort='id';
$invoice_all = $invoices->select_all('count');

$get_cron = new cron();
$get_cron->id = $_GET['id'];
$cron = $get_cron->select();

$smarty -> assign('invoice_all',$invoice_all);
$smarty -> assign('saved',$saved);
$smarty -> assign('cron',$cron);

$smarty -> assign('pageActive', 'cron');
$smarty -> assign('subPageActive', 'cron_edit');
$smarty -> assign('active_tab', '#money');