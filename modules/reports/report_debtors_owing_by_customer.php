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
<b>Debtors - Amount owing per Customer</b>
<hr></hr>
<div id="container">

<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "SELECT
        {$tb_prefix}customers.id as CID,
        {$tb_prefix}customers.name as Customer,
        (select sum({$tb_prefix}invoice_items.total) from {$tb_prefix}invoice_items,{$tb_prefix}invoices where  {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id) as INV_TOTAL,
        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where {$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id) as INV_PAID,
        (select (INV_TOTAL - INV_PAID)) as INV_OWING

FROM
        {$tb_prefix}customers,{$tb_prefix}invoices,{$tb_prefix}invoice_items
WHERE
        {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id
GROUP BY
        INV_OWING DESC;


   ";	
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_owing_by_customer.xml");
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
