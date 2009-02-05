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

	$sql = "SELECT count(*) as count FROM ".TB_PREFIX."invoices";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$number_of_invoices  = $sth->fetch(PDO::FETCH_ASSOC);

//all funky xml - sql stuff done in xml.php

$pageActive = "invoice";

$smarty -> assign("invoices",$invoices);
$smarty -> assign("number_of_invoices",$number_of_invoices);
$smarty -> assign("spreadsheet",$spreadsheet);
$smarty -> assign("word_processor",$word_processor);

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#money');

?>
