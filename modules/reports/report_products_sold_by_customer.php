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

</head>
<body>
<b>Products Sold - Group by Customer - Total</b>
<hr></hr>
<div id="container">

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select sum({$tb_prefix}invoice_items.quantity) as SUM_QUANTITY, {$tb_prefix}customers.name, {$tb_prefix}products.description  from  {$tb_prefix}customers, {$tb_prefix}invoice_items, {$tb_prefix}invoices, {$tb_prefix}products  where  {$tb_prefix}invoice_items.product_id = {$tb_prefix}products.id and {$tb_prefix}invoices.customer_id =  {$tb_prefix}customers.id and {$tb_prefix}invoices.id = {$tb_prefix}invoice_items.invoice_id GROUP BY {$tb_prefix}invoice_items.quantity ORDER BY name";

   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_products_sold_by_customer.xml");
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
