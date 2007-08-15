<?php 
//include("./include/include_main.php");

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

   $sSQL = "select sum(".TB_PREFIX."invoice_items.quantity) as SUM_QUANTITY, ".TB_PREFIX."customers.name, ".TB_PREFIX."products.description  from  ".TB_PREFIX."customers, ".TB_PREFIX."invoice_items, ".TB_PREFIX."invoices, ".TB_PREFIX."products  where  ".TB_PREFIX."invoice_items.product_id = ".TB_PREFIX."products.id and ".TB_PREFIX."invoices.customer_id =  ".TB_PREFIX."customers.id and ".TB_PREFIX."invoices.id = ".TB_PREFIX."invoice_items.invoice_id GROUP BY ".TB_PREFIX."invoice_items.quantity ORDER BY name";

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
