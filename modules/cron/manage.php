<?php
/*
* Script: manage.php
* 	Manage Invoices page
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

	$sql = "SELECT count(*) as count FROM ".TB_PREFIX."cron where domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id',domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
	$number_of_crons  = $sth->fetch(PDO::FETCH_ASSOC);

//all funky xml - sql stuff done in xml.php


//$smarty -> assign("invoices",$invoices);
$smarty -> assign("number_of_crons",$number_of_crons);

$smarty -> assign('pageActive', 'cron');
$smarty -> assign('active_tab', '#money');

$url =  'index.php?module=cron&view=xml';

$smarty -> assign('url', $url);
