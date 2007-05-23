<?php
include("./include/include_main.php"); 

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- CSS -->

</head>
<body>

<b>Sales by Customer - Group by Customer - Total</b>
<hr />
<div class=container>

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select sum({$tb_prefix}invoice_items.total) as SUM_TOTAL, {$tb_prefix}biller.name as BNAME, {$tb_prefix}customers.name as CNAME from {$tb_prefix}biller, {$tb_prefix}customers, {$tb_prefix}invoice_items, {$tb_prefix}invoices where {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id and {$tb_prefix}invoices.biller_id = {$tb_prefix}biller.id and {$tb_prefix}invoices.id = {$tb_prefix}invoice_items.invoice_id GROUP BY {$tb_prefix}invoice_items.total ORDER BY {$tb_prefix}biller.name";

   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_biller_by_customer.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("$db_name");
   $oRpt->run();
?>
	
<hr></hr>
</div>
<div id="footer"></div>
