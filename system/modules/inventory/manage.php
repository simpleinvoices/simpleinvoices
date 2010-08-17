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

	$sql = "SELECT count(*) as count FROM ".TB_PREFIX."inventory where domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id',domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
	$number_of_rows  = $sth->fetch(PDO::FETCH_ASSOC);

//all funky xml - sql stuff done in xml.php


//$smarty -> assign("invoices",$invoices);
$smarty -> assign("number_of_rows",$number_of_rows);

$smarty -> assign('pageActive', 'inventory');
$smarty -> assign('active_tab', '#product');

$url =  'index.php?module=inventory&view=xml';

$smarty -> assign('url', $url);
