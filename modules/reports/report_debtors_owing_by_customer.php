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
        ".TB_PREFIX."customers.id as CID,
        ".TB_PREFIX."customers.name as Customer,
        (select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items,".TB_PREFIX."invoices where  ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id) as INV_TOTAL,
        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where ".TB_PREFIX."account_payments.ac_inv_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id) as INV_PAID,
        (select (INV_TOTAL - INV_PAID)) as INV_OWING

FROM
        ".TB_PREFIX."customers,".TB_PREFIX."invoices,".TB_PREFIX."invoice_items
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id
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
