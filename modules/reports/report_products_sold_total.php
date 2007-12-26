<?php 
//   include phpreports library
require_once("./include/reportlib.php");

	$sSQL  = "SELECT p.description As Product, sum(ivt.quantity) AS SUM_QUANTITY ";
	$sSQL .= "FROM  ".TB_PREFIX."invoice_items ivt, ".TB_PREFIX."invoices iv, ";
	$sSQL .= 		  TB_PREFIX."products p ";
	$sSQL .= "WHERE iv.id = ivt.invoice_id AND ivt.product_id = p.id AND p.visible ";
	$sSQL .= "GROUP BY Product";

	$oRpt->setXML("./modules/reports/report_products_sold_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>