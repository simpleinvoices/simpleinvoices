<?php
require_once("./include/reportlib.php");

   $sSQL = "SELECT sum(ii.quantity) as sum_quantity, c.name, p.description
      FROM ".TB_PREFIX."customers c INNER JOIN
      ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id) INNER JOIN
      ".TB_PREFIX."products p ON (p.id = ii.product_id)
      WHERE p.visible
      GROUP BY p.description, c.name
      ORDER BY c.name";

   $oRpt->setXML("./modules/reports/report_products_sold_by_customer.xml");

	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>