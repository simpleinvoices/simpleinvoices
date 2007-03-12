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

<script type="text/javascript" src="./src/include/js/ibox.js"></script>
<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>


</head>
<body>
<br>
<b>Debtor invoices ordered by aging</b>
<hr></hr>
<div id="container">

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./src/reports/PHPReportMaker.php";

   $sSQL = "

SELECT
        inv_id,
        (select b_name from {$tb_prefix}biller where b_id = {$tb_prefix}invoices.inv_biller_id) as Biller,
        (select c_name from {$tb_prefix}customers where c_id = {$tb_prefix}invoices.inv_customer_id) as Customer,
        (select sum(inv_it_total) from {$tb_prefix}invoice_items WHERE inv_it_invoice_id = inv_id) as Total,
        ( select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from {$tb_prefix}account_payments where  ac_inv_id = inv_id ) as Paid,
        (select (Total - Paid)) as Owing ,
        date_format(inv_date,'%Y-%m-%e') as Date ,
        (select datediff(now(),inv_date)) as Age,
        (CASE WHEN datediff(now(),inv_date) <= 14 THEN '0-14'
                WHEN datediff(now(),inv_date) <= 30 THEN '15-30'
                WHEN datediff(now(),inv_date) <= 60 THEN '31-60'
                WHEN datediff(now(),inv_date) <= 90 THEN '61-90'
                ELSE '90+'
        END ) as Aging

FROM
        {$tb_prefix}invoices,{$tb_prefix}account_payments,{$tb_prefix}invoice_items, {$tb_prefix}biller, {$tb_prefix}customers
WHERE
        inv_it_invoice_id = {$tb_prefix}invoices.inv_id
GROUP BY
        inv_id;

";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_debtors_by_aging.xml");
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
