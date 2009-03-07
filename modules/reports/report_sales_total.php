<?php 
//   include phpreports library
require_once("./include/reportlib.php");

   $sSQL = "select sum(ii.total) as total from ".TB_PREFIX."invoice_items ii";

   $oRpt->setXML("./modules/reports/report_sales_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>