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
<b>Totals by Aging periods</b>
<hr></hr>
<div id="container">

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";

   $sSQL = "SELECT

        (CASE WHEN datediff(now(),date) <= 14 THEN (select IF ( isnull(sum({$tb_prefix}invoice_items.total)) , '0', sum({$tb_prefix}invoice_items.total)) from {$tb_prefix}invoices,{$tb_prefix}invoice_items where datediff(now(),date) <= 14 and {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id)
                WHEN datediff(now(),date) <= 30 THEN (select  IF ( isnull(sum({$tb_prefix}invoice_items.total)) , '0', sum({$tb_prefix}invoice_items.total)) from {$tb_prefix}invoices,{$tb_prefix}invoice_items where datediff(now(),date) <= 30 and datediff(now(),date) > 14 and {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id)
                WHEN datediff(now(),date) <= 60 THEN (select  IF ( isnull(sum({$tb_prefix}invoice_items.total)) , '0', sum({$tb_prefix}invoice_items.total)) from {$tb_prefix}invoices,{$tb_prefix}invoice_items where datediff(now(),date) <= 60 and datediff(now(),date) > 30 and {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id)
                WHEN datediff(now(),date) <= 90 THEN (select  IF ( isnull(sum({$tb_prefix}invoice_items.total)) , '0', sum({$tb_prefix}invoice_items.total)) from {$tb_prefix}invoices,{$tb_prefix}invoice_items where datediff(now(),date) <= 90 and datediff(now(),date) > 60 and {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id)
                ELSE (select  IF ( isnull(sum({$tb_prefix}invoice_items.total)) , '0', sum({$tb_prefix}invoice_items.total)) from {$tb_prefix}invoices,{$tb_prefix}invoice_items where datediff(now(),date) > 90 and {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id)
        END ) as INV_TOTAL,

        (CASE WHEN datediff(now(),date) <= 14 THEN (select  IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where datediff(now(),date) <= 14 and ac_inv_id = {$tb_prefix}invoices.id)
                WHEN datediff(now(),date) <= 30 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where datediff(now(),date) > 14 and datediff(now(),date) <= 30 and ac_inv_id = {$tb_prefix}invoices.id )
                WHEN datediff(now(),date) <= 60 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where datediff(now(),date) > 30 and datediff(now(),date) <= 60 and ac_inv_id = {$tb_prefix}invoices.id )
                WHEN datediff(now(),date) <= 90 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where datediff(now(),date) > 60 and datediff(now(),date) <= 90 and ac_inv_id = {$tb_prefix}invoices.id )
                ELSE (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where datediff(now(),date) > 90 and ac_inv_id = {$tb_prefix}invoices.id )          
	  END ) as INV_PAID,

        (select (INV_TOTAL - INV_PAID)) as INV_OWING,

        (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'
                WHEN datediff(now(),date) <= 30 THEN '15-30'
                WHEN datediff(now(),date) <= 60 THEN '31-60'
                WHEN datediff(now(),date) <= 60 THEN '61-90'
                ELSE '90+'
        END ) as Aging

FROM
        {$tb_prefix}invoices,{$tb_prefix}account_payments,{$tb_prefix}invoice_items, {$tb_prefix}biller, {$tb_prefix}customers
WHERE
        {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id
GROUP BY
        INV_TOTAL;


";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_aging_total.xml");
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
