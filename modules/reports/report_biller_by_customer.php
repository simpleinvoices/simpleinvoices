<?php
//   include phpreports library
require_once("./include/reportlib.php");

// Adjusted for NULL on computation with NULL values
   $sSQL = "SELECT
      sum(ii.total) AS sum_total,
         b.name AS bname,
         c.name AS cname
      FROM ".TB_PREFIX."biller b INNER JOIN
      ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id) INNER JOIN
      ".TB_PREFIX."customers c ON (c.id = iv.customer_id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
      GROUP BY b.name, c.name";

	
	$oRpt->setXML("./modules/reports/report_biller_by_customer.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");


?>

