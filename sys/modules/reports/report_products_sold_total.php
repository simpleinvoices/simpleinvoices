<?php 
//   include phpreports library
require_once($include_dir . "sys/include/reportlib.php");

   $sSQL = "SELECT p.description, sum(ii.quantity) AS sum_quantity
      FROM ".TB_PREFIX."invoice_items ii INNER JOIN
      ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id) INNER JOIN
      ".TB_PREFIX."products p ON (p.id = ii.product_id)
      WHERE p.visible GROUP BY p.description";

   $oRpt->setXML($include_dir . "sys/modules/reports/report_products_sold_total.xml");
   
//   include phpreports run code
	include($include_dir . "sys/include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>