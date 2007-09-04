<?php


	//stop the direct browsing to this file - let index.php handle which files get displayed
	checkLogin();

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select sum(".TB_PREFIX."invoice_items.total) as SUM_TOTAL, ".TB_PREFIX."biller.name as BNAME, ".TB_PREFIX."customers.name as CNAME from ".TB_PREFIX."biller, ".TB_PREFIX."customers, ".TB_PREFIX."invoice_items, ".TB_PREFIX."invoices where ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id and ".TB_PREFIX."invoices.biller_id = ".TB_PREFIX."biller.id and ".TB_PREFIX."invoices.id = ".TB_PREFIX."invoice_items.invoice_id GROUP BY ".TB_PREFIX."invoice_items.total ORDER BY ".TB_PREFIX."biller.name";

   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_biller_by_customer.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("$db_name");
   $showReport = $oRpt->run();
   
   $pageActive = "reports";

	$smarty->assign('pageActive', $pageActive);
	$smarty->assign('showReport', $showReport);

?>

