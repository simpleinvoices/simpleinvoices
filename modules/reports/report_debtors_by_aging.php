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


</head>
<body>
<br>
<b>Debtor invoices ordered by aging</b>
<hr></hr>
<div id="container">

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";

   $sSQL = "SELECT
        {$tb_prefix}invoices.id,
        (select name from {$tb_prefix}biller where {$tb_prefix}biller.id = {$tb_prefix}invoices.biller_id) as Biller,
        (select name from {$tb_prefix}customers where {$tb_prefix}customers.id = {$tb_prefix}invoices.customer_id) as Customer,
        (select sum({$tb_prefix}invoice_items.total) from {$tb_prefix}invoice_items WHERE {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id) as INV_TOTAL,
        ( select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from {$tb_prefix}account_payments where  ac_inv_id = {$tb_prefix}invoices.id ) as INV_PAID,
        (select (INV_TOTAL - INV_PAID)) as INV_OWING ,
        date_format(date,'%Y-%m-%e') as Date ,
        (select datediff(now(),date)) as Age,
        (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'
                WHEN datediff(now(),date) <= 30 THEN '15-30'
                WHEN datediff(now(),date) <= 60 THEN '31-60'
                WHEN datediff(now(),date) <= 90 THEN '61-90'
                ELSE '90+'
        END ) as Aging

FROM
        {$tb_prefix}invoices,{$tb_prefix}account_payments,{$tb_prefix}invoice_items, {$tb_prefix}biller, {$tb_prefix}customers
WHERE
        {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id
GROUP BY
        {$tb_prefix}invoices.id;

";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_by_aging.xml");
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
