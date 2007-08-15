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
<b>Products sold in total by Product</b>
<hr></hr>
<div id="container">

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select ".TB_PREFIX."products.description, sum(".TB_PREFIX."invoice_items.quantity) AS SUM_QUANTITY from  ".TB_PREFIX."invoice_items, ".TB_PREFIX."invoices, ".TB_PREFIX."products where ".TB_PREFIX."invoices.id = ".TB_PREFIX."invoice_items.invoice_id and ".TB_PREFIX."invoice_items.product_id = ".TB_PREFIX."products.id GROUP BY description";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_products_sold_total.xml");
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
