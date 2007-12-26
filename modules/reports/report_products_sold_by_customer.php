<?php
//   include phpreports library
require_once("./include/reportlib.php");

	$sSQL  = "SELECT sum(ivt.quantity) as SUM_QUANTITY, c.name As Customer, p.description As Product ";
	$sSQL .= "FROM  ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_items ivt, ";
	$sSQL .= 		  TB_PREFIX."invoices iv, ".TB_PREFIX."products p ";
	$sSQL .= "WHERE ivt.product_id = p.id AND iv.customer_id =  c.id AND ";
	$sSQL .= "		iv.id = ivt.invoice_id and p.visible ";
	$sSQL .= "GROUP BY Customer, Product;";

	$oRpt->setXML("./modules/reports/report_products_sold_by_customer.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");
?>