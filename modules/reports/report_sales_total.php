<?php 
//   include phpreports library
require_once("./include/reportlib.php");

	$sSQL = "SELECT sum(ivt.total) as Total FROM ".TB_PREFIX."invoice_items ivt";

	$oRpt->setXML("./modules/reports/report_sales_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>

