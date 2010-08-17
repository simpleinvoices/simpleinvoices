<?php
/*
* Script: manage.php
* 	Manage Invoices page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

	$sql = "SELECT count(*) as count FROM ".TB_PREFIX."invoices";
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	$number_of_invoices  = $sth->fetch(PDO::FETCH_ASSOC);
//all funky xml - sql stuff done in xml.php

$pageActive = "invoices";

$smarty -> assign("invoices",$invoices);
$smarty -> assign("number_of_invoices",$number_of_invoices);
$smarty -> assign("spreadsheet",$spreadsheet);
$smarty -> assign("word_processor",$word_processor);
$smarty -> assign('pageActive', $pageActive);

$page = empty($_GET['page'])? "1" :$_GET['page'] ;
$page_prev = ($page =="1") ? "1" : $page-1 ;
$page_next =  $page+1 ;
//$xml_file = './extensions/text_ui/modules/invoices/xml.php';
$url=getURL();
$xml_file = $url.'/index.php?module=invoices&view=xml&page='.$page;
$xml = simplexml_load_file($xml_file);

$smarty -> assign('xml', $xml);
$smarty -> assign('page', $page);
$smarty -> assign('page_prev', $page_prev);
$smarty -> assign('page_next', $page_next);
?>
