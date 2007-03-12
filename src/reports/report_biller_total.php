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
<!-- thickbox js and css stuff -->
<script type="text/javascript" src="./src/include/js/ibox.js"></script>
<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>

</head>
<body>
<b>Sales in total by Biller</b>
<hr></hr>
<div id=container>

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./src/reports/PHPReportMaker.php";

   $sSQL = "select  {$tb_prefix}biller.b_name,  sum({$tb_prefix}invoice_items.inv_it_total) from {$tb_prefix}biller, {$tb_prefix}invoice_items, {$tb_prefix}invoices where {$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.b_id and {$tb_prefix}invoices.inv_id = {$tb_prefix}invoice_items.inv_it_invoice_id GROUP BY b_name";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./src/reports/xml/report_biller_total.xml");
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
<a href="./documentation/info_pages/reports_xsl.html" rel="ibox&height=400"><font color="red">Did you get an "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
