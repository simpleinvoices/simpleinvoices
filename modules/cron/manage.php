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

	$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."cron WHERE domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', domain_id::get());
	$number_of_crons  = $sth->fetch(PDO::FETCH_ASSOC);

//all funky xml - sql stuff done in xml.php


//$bladeView -> assign("invoices",$invoices);
$bladeView -> assign("number_of_crons", $number_of_crons);

$bladeView -> assign('pageActive', 'cron');
$bladeView -> assign('active_tab', '#money');

$url =  'index.php?module=cron&view=xml';

$bladeView -> assign('url', $url);
