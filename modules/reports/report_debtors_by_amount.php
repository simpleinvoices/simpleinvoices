<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";

   $sSQL = "SELECT
        ".TB_PREFIX."invoices.id,
        (select name from ".TB_PREFIX."biller where ".TB_PREFIX."biller.id = ".TB_PREFIX."invoices.biller_id) as Biller,
        (select name from ".TB_PREFIX."customers where id = ".TB_PREFIX."invoices.customer_id) as Customer,
        (select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items WHERE ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id) as INV_TOTAL,
        ( select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."account_payments where  ac_inv_id = ".TB_PREFIX."invoices.id ) as INV_PAID,
        (select (INV_TOTAL - INV_PAID)) as INV_OWING ,
        date
FROM
        ".TB_PREFIX."invoices,".TB_PREFIX."account_payments,".TB_PREFIX."invoice_items, ".TB_PREFIX."biller, ".TB_PREFIX."customers
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id
GROUP BY
        ".TB_PREFIX."invoices.id
ORDER BY
        INV_OWING DESC;

";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_by_amount.xml");
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
