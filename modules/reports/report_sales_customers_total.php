<?php 
//   include phpreports library
require_once("./include/reportlib.php");

	$sSQL  = "SELECT c.name As Customer, sum(ivt.total) as SUM_TOTAL ";
	$sSQL .= "FROM ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_items ivt, ";
	$sSQL .= 		 TB_PREFIX."invoices iv ";
	$sSQL .= "WHERE iv.customer_id = c.id AND iv.id = ivt.invoice_id ";
	$sSQL .= "GROUP BY c.name ";

	$oRpt->setXML("./modules/reports/report_sales_customers_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>