<?php
//   include phpreports library
require_once("./include/reportlib.php");

   $sSQL = "SELECT b.name, sum(ii.total) AS sum_total
      FROM ".TB_PREFIX."biller b INNER JOIN
      ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id)
      GROUP BY b.name";

   $oRpt->setXML("./modules/reports/report_biller_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>