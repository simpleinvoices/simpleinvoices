<?php
/*
 * Script: manage.php
 *     Customers manage page
 *
 * License:
 *     GPL v2 or above
 *
 * Website:
 *     http://www.simpleinvoices.org
 */
global $dbh, $smarty;
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id',domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_customers  = $sth->fetch(PDO::FETCH_ASSOC);

$pageActive = "customers";

$smarty -> assign('number_of_customers', $number_of_customers);
$smarty -> assign('pageActive', $pageActive);

$customers = null; // add by RCR to eliminate undefined warning. TODO: See if needs to be set.
$smarty -> assign("customers" ,$customers);

$page = empty($_GET['page'])? "1" :$_GET['page'] ;
$page_prev = ($page =="1") ? "1" : $page-1 ;
$page_next =  $page+1 ;
//$xml_file = './extensions/text_ui/modules/invoices/xml.php';
$url=getURL();
$xml_file = $url.'/index.php?module=customers&view=xml&page='.$page;
$xml = simplexml_load_file($xml_file);

$smarty -> assign('xml', $xml);
$smarty -> assign('page', $page);
$smarty -> assign('page_prev', $page_prev);
$smarty -> assign('page_next', $page_next);
