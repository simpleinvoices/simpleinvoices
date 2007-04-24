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
<b>Debtor invoices ordered by amount owed</b>
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
        inv_date
FROM
        {$tb_prefix}invoices,{$tb_prefix}account_payments,{$tb_prefix}invoice_items, {$tb_prefix}biller, {$tb_prefix}customers
WHERE
        inv_it_invoice_id = {$tb_prefix}invoices.inv_id
GROUP BY
        inv_id
ORDER BY
        Owing DESC;

";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_debtors_by_amount.xml");
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
<a href="./src/documentation/info_pages/reports_xsl.html" rel="gb_page_center[450, 450]"><font color="red">Did you get an "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
