<?php
//   include phpreports library
require_once("./include/reportlib.php");

// Adjusted for NULL on computation with NULL values


	$sSQL  = "SELECT sum(ivt.total) as SUM_TOTAL, b.name as Biller, c.name as Customer ";
	$sSQL .= "FROM ".TB_PREFIX."biller b, ".TB_PREFIX."customers c, ";
	$sSQL .= 		 TB_PREFIX."invoice_items ivt, ".TB_PREFIX."invoices iv ";
	$sSQL .= "WHERE iv.customer_id = c.id AND iv.biller_id = b.id AND iv.id = ivt.invoice_id ";
	$sSQL .= "GROUP BY ivt.total ORDER BY b.name";
	
	$oRpt->setXML("./modules/reports/report_biller_by_customer.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>

