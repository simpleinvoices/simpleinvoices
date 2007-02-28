<?php include("./include/include_main.php"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- thickbox js and css stuff -->
<script type="text/javascript" src="./src/include/js/ibox.js"></script>
<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>

</head>
<body>
<b>Products Sold - Group by Customer - Total</b>
<hr></hr>
<div id="container">

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "src/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select  sum(si_invoice_items.inv_it_quantity), si_customers.c_name, si_products.prod_description  from  si_customers, si_invoice_items, si_invoices, si_products  where  si_invoice_items.inv_it_product_id = si_products.prod_id and si_invoices.inv_customer_id =  si_customers.c_id and si_invoices.inv_id = si_invoice_items.inv_it_invoice_id GROUP BY inv_it_quantity ORDER BY c_name";

   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_products_sold_by_customer.xml");
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
