<?php 
include("./include/include_main.php"); 

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

?>
<b>Sales in total by Customer</b>
<hr></hr>
<div id="container">


<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "src/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select  {$tb_prefix}customers.c_name,  sum({$tb_prefix}invoice_items.inv_it_total) from {$tb_prefix}customers, {$tb_prefix}invoice_items, {$tb_prefix}invoices where {$tb_prefix}invoices.inv_customer_id = {$tb_prefix}customers.c_id and {$tb_prefix}invoices.inv_id = {$tb_prefix}invoice_items.inv_it_invoice_id GROUP BY c_name ";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_sales_customers_total.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("$db_name");
   $oRpt->run();
?>
	
</div>

<div id="footer"></div>
<hr></hr>
<a href="./documentation/info_pages/reports_xsl.html" rel="gb_page_center[450, 450]"><font color="red">Did you get an "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
