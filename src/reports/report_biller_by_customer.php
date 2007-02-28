<?php include("./include/include_main.php"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- CSS -->
<script type="text/javascript" src="./src/include/js/ibox.js"></script>
<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>

</head>
<body>

<b>Sales by Customer - Group by Customer - Total</b>
<hr></hr>
<div class=container>

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "src/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select sum(si_invoice_items.inv_it_total), si_biller.b_name, si_customers.c_name from si_biller, si_customers, si_invoice_items, si_invoices where si_invoices.inv_customer_id = si_customers.c_id and si_invoices.inv_biller_id = si_biller.b_id and si_invoices.inv_id = si_invoice_items.inv_it_invoice_id GROUP BY inv_it_total ORDER BY si_biller.b_name";

   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./src/reports/xml/report_biller_by_customer.xml");
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
