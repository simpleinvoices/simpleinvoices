<?php 
//include("./include/include_main.php");

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

        (CASE WHEN datediff(now(),date) <= 14 THEN (select IF ( isnull(sum(".TB_PREFIX."invoice_items.total)) , '0', sum(".TB_PREFIX."invoice_items.total)) from ".TB_PREFIX."invoices,".TB_PREFIX."invoice_items where datediff(now(),date) <= 14 and ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id)
                WHEN datediff(now(),date) <= 30 THEN (select  IF ( isnull(sum(".TB_PREFIX."invoice_items.total)) , '0', sum(".TB_PREFIX."invoice_items.total)) from ".TB_PREFIX."invoices,".TB_PREFIX."invoice_items where datediff(now(),date) <= 30 and datediff(now(),date) > 14 and ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id)
                WHEN datediff(now(),date) <= 60 THEN (select  IF ( isnull(sum(".TB_PREFIX."invoice_items.total)) , '0', sum(".TB_PREFIX."invoice_items.total)) from ".TB_PREFIX."invoices,".TB_PREFIX."invoice_items where datediff(now(),date) <= 60 and datediff(now(),date) > 30 and ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id)
                WHEN datediff(now(),date) <= 90 THEN (select  IF ( isnull(sum(".TB_PREFIX."invoice_items.total)) , '0', sum(".TB_PREFIX."invoice_items.total)) from ".TB_PREFIX."invoices,".TB_PREFIX."invoice_items where datediff(now(),date) <= 90 and datediff(now(),date) > 60 and ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id)
                ELSE (select  IF ( isnull(sum(".TB_PREFIX."invoice_items.total)) , '0', sum(".TB_PREFIX."invoice_items.total)) from ".TB_PREFIX."invoices,".TB_PREFIX."invoice_items where datediff(now(),date) > 90 and ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id)
        END ) as INV_TOTAL,

        (CASE WHEN datediff(now(),date) <= 14 THEN (select  IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where datediff(now(),date) <= 14 and ac_inv_id = ".TB_PREFIX."invoices.id)
                WHEN datediff(now(),date) <= 30 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where datediff(now(),date) > 14 and datediff(now(),date) <= 30 and ac_inv_id = ".TB_PREFIX."invoices.id )
                WHEN datediff(now(),date) <= 60 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where datediff(now(),date) > 30 and datediff(now(),date) <= 60 and ac_inv_id = ".TB_PREFIX."invoices.id )
                WHEN datediff(now(),date) <= 90 THEN (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where datediff(now(),date) > 60 and datediff(now(),date) <= 90 and ac_inv_id = ".TB_PREFIX."invoices.id )
                ELSE (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where datediff(now(),date) > 90 and ac_inv_id = ".TB_PREFIX."invoices.id )          
	  END ) as INV_PAID,

        (select (INV_TOTAL - INV_PAID)) as INV_OWING,

        (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'
                WHEN datediff(now(),date) <= 30 THEN '15-30'
                WHEN datediff(now(),date) <= 60 THEN '31-60'
                WHEN datediff(now(),date) <= 60 THEN '61-90'
                ELSE '90+'
        END ) as Aging

FROM
        ".TB_PREFIX."invoices,".TB_PREFIX."account_payments,".TB_PREFIX."invoice_items, ".TB_PREFIX."biller, ".TB_PREFIX."customers
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id
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
   ob_start();
   $oRpt->run();
   $showReport = ob_get_contents();
   
   ob_end_clean();

   
   $pageActive = "reports";

	$smarty->assign('pageActive', $pageActive);
	$smarty->assign('showReport', $showReport);
?>