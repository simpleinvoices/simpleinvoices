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
<b>Products sold in total by Product</b>
<hr></hr>
<div id="container">

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "src/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select  si_products.prod_description, sum(si_invoice_items.inv_it_quantity) from  si_invoice_items, si_invoices, si_products where si_invoices.inv_id = si_invoice_items.inv_it_invoice_id and si_invoice_items.inv_it_product_id = si_products.prod_id GROUP BY prod_description";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_products_sold_total.xml");
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
<a href="./documentation/info_pages/reports_xsl.html?keepThis=true&TB_iframe=true&height=300&width=650" title="Info :: Reports" class="thickbox"><font color="red">Did you get an "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
