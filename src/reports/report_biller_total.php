<?php include("./include/include_main.php"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- thickbox js and css stuff -->
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/jquery.thickbox.js"></script>

    <link rel="stylesheet" type="text/css" href="src/include/css/jquery.thickbox.css" media="all"/>

</head>
<body>
<b>Sales in total by Biller</b>
<hr></hr>
<div id=container>

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./src/reports/PHPReportMaker.php";

   $sSQL = "select  si_biller.b_name,  sum(si_invoice_items.inv_it_total) from si_biller, si_invoice_items, si_invoices where si_invoices.inv_biller_id = si_biller.b_id and si_invoices.inv_id = si_invoice_items.inv_it_invoice_id GROUP BY b_name";
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

<div id="footer"></div>
<hr></hr>
<a href="./documentation/info_pages/reports_xsl.html?keepThis=true&TB_iframe=true&height=300&width=650" title="Info :: Reports" class="thickbox"><font color="red">Got "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
