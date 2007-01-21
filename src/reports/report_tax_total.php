<?php include("./include/include_main.php"); ?>
<html>
<head>
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/jquery.thickbox.js"></script>
</head>
<body>
<b>Taxes in total</b>
<hr></hr>
<div id="container">

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "src/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select  sum(inv_it_tax_amount) from si_invoice_items";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_tax_total.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("$db_name");
   $oRpt->run();
?>
<div id="footer"></div>
<hr></hr>
 <a href="./documentation/info_pages/reports_xsl.html?keepThis=true&TB_iframe=true&height=300&width=650" title="Info :: Reports" class="thickbox"><font color="red">Got "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
