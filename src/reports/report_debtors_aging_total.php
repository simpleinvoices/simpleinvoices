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
<br>
<b>Totals by Aging periods</b>
<hr></hr>
<div id="container">

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./src/reports/PHPReportMaker.php";

   $sSQL = "

SELECT

        (CASE WHEN datediff(now(),inv_date) <= 14 THEN (select IF ( isnull(sum(inv_it_total)) , '0', sum(inv_it_total)) from si_invoices,si_invoice_items where datediff(now(),inv_date) <= 14 and inv_it_invoice_id = si_invoices.inv_id)
                WHEN datediff(now(),inv_date) <= 30 THEN (select  IF ( isnull(sum(inv_it_total)) , '0', sum(inv_it_total)) from si_invoices,si_invoice_items where datediff(now(),inv_date) <= 30 and datediff(now(),inv_date) > 14 and inv_it_invoice_id = si_invoices.inv_id)
                WHEN datediff(now(),inv_date) <= 60 THEN (select  IF ( isnull(sum(inv_it_total)) , '0', sum(inv_it_total)) from si_invoices,si_invoice_items where datediff(now(),inv_date) <= 60 and datediff(now(),inv_date) > 30 and inv_it_invoice_id = si_invoices.inv_id)
                WHEN datediff(now(),inv_date) <= 90 THEN (select  IF ( isnull(sum(inv_it_total)) , '0', sum(inv_it_total)) from si_invoices,si_invoice_items where datediff(now(),inv_date) <= 90 and datediff(now(),inv_date) > 60 and inv_it_invoice_id = si_invoices.inv_id)
                ELSE (select  IF ( isnull(sum(inv_it_total)) , '0', sum(inv_it_total)) from si_invoices,si_invoice_items where datediff(now(),inv_date) > 90 and inv_it_invoice_id = si_invoices.inv_id)
        END ) as Total,

        (CASE WHEN datediff(now(),inv_date) <= 14 THEN (select  IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from si_account_payments,si_invoices where datediff(now(),inv_date) <= 14 and ac_inv_id = si_invoices.inv_id)
                WHEN datediff(now(),inv_date) <= 30 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from si_account_payments,si_invoices where datediff(now(),inv_date) > 14 and datediff(now(),inv_date) <= 30 and ac_inv_id = si_invoices.inv_id )
                WHEN datediff(now(),inv_date) <= 60 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from si_account_payments,si_invoices where datediff(now(),inv_date) > 30 and datediff(now(),inv_date) <= 60 and ac_inv_id = si_invoices.inv_id )
                WHEN datediff(now(),inv_date) <= 90 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from si_account_payments,si_invoices where datediff(now(),inv_date) > 60 and datediff(now(),inv_date) <= 90 and ac_inv_id = si_invoices.inv_id )
                ELSE (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from si_account_payments,si_invoices where datediff(now(),inv_date) > 90 and ac_inv_id = si_invoices.inv_id )          
	  END ) as Paid,

        (select (Total - Paid)) as Owing,

        (CASE WHEN datediff(now(),inv_date) <= 14 THEN '0-14'
                WHEN datediff(now(),inv_date) <= 30 THEN '15-30'
                WHEN datediff(now(),inv_date) <= 60 THEN '31-60'
                WHEN datediff(now(),inv_date) <= 60 THEN '61-90'
                ELSE '90+'
        END ) as Aging

FROM
        si_invoices,si_account_payments,si_invoice_items, si_biller, si_customers
WHERE
        inv_it_invoice_id = si_invoices.inv_id
GROUP BY
        Total;


";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./src/reports/xml/report_debtors_aging_total.xml");
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
