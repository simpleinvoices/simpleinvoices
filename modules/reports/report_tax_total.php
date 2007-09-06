<?php
/* The Export code - supports any file extensions - excel/word/open office - what reads html */
if (isset($_GET['export'])) {
	$template = "export";
	$file_extension = $_GET['export'];
	header("Content-type: application/octet-stream");
	/*header("Content-type: application/x-msdownload");*/
	header("Content-Disposition: attachment; filename=test.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
/* End Export code */

//include("./include/include_main.php"); 

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select sum(".TB_PREFIX."invoice_items.tax_amount) as SUM_TAX_AMOUNT from ".TB_PREFIX."invoice_items";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_tax_total.xml");
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
