<?php 
//include("./include/include_main.php"); 

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}


   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "./config/config.php";

   $sSQL = "SELECT c.name, sum(ii.total) as sum_total
      FROM ".TB_PREFIX."customers c INNER JOIN
      ".TB_PREFIX."invoices iv ON (iv.customer_id = c.id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id)
      GROUP BY c.name ";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_sales_customers_total.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_server:host=$db_host");
   $oRpt->setDatabaseInterface("pdo");
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
