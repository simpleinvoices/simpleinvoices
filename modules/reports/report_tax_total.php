<?php

//   include phpreports library
require_once("./include/reportlib.php");

	$sSQL = "select sum(ivt.tax_amount) as SUM_TAX_AMOUNT from ".TB_PREFIX."invoice_items ivt";

	$oRpt->setXML("./modules/reports/report_tax_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>