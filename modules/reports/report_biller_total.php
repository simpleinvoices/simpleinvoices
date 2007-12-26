<?php
//   include phpreports library
require_once("./include/reportlib.php");

	$sSQL = "select  b.name,  sum(ivt.total) as SUM_TOTAL from ".TB_PREFIX."biller b, ".TB_PREFIX."invoice_items ivt, ".TB_PREFIX."invoices iv where iv.biller_id = b.id and iv.id = ivt.invoice_id GROUP BY name";

	$oRpt->setXML("./modules/reports/report_biller_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>