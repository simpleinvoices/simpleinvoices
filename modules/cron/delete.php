<?php

checkLogin();

	$sql = "DELETE FROM ".TB_PREFIX."cron WHERE id = :id AND domain_id = :domain_id";
	$sth = $db->query($sql, ':id', $_GET['id'], ':domain_id',domain_id::get());
	$saved = !empty($sth) ? "true" : "false";

$invoices = new invoice();
$invoices->sort='id';
$invoice_all = $invoices->select_all('count');

$smarty -> assign('invoice_all',$invoice_all);
$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'cron');
$smarty -> assign('subPageActive', 'cron_manage');
$smarty -> assign('active_tab', '#money');
