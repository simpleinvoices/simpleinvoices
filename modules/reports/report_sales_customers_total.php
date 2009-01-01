<?php 
//   include phpreports library
require_once("./include/reportlib.php");

   $sSQL = "SELECT c.name, sum(ii.total) as sum_total
      FROM ".TB_PREFIX."customers c INNER JOIN
      ".TB_PREFIX."invoices iv ON (iv.customer_id = c.id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id)
      GROUP BY c.name ";

   $oRpt->setXML("./modules/reports/report_sales_customers_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
