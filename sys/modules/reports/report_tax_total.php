<?php
//   include phpreports library
require_once($include_dir . "sys/include/reportlib.php");

   $sSQL = "select sum(ii.tax_amount) as sum_tax_total from ".TB_PREFIX."invoice_items ii";

   $oRpt->setXML($include_dir . "sys/modules/reports/report_tax_total.xml");

//   include phpreports run code
	include($include_dir . "sys/include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
