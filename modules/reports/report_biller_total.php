<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";

   $sSQL = "select  ".TB_PREFIX."biller.name,  sum(".TB_PREFIX."invoice_items.total) as SUM_TOTAL from ".TB_PREFIX."biller, ".TB_PREFIX."invoice_items, ".TB_PREFIX."invoices where ".TB_PREFIX."invoices.biller_id = ".TB_PREFIX."biller.id and ".TB_PREFIX."invoices.id = ".TB_PREFIX."invoice_items.invoice_id GROUP BY name";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_biller_total.xml");
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
