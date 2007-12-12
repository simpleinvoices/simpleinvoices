<?php


   //stop the direct browsing to this file - let index.php handle which files get displayed
   checkLogin();

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "./config/config.php";

   $sSQL = "SELECT
      sum(ii.total) AS sum_total,
         b.name AS bname,
         c.name AS cname
      FROM ".TB_PREFIX."biller b INNER JOIN
      ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id) INNER JOIN
      ".TB_PREFIX."customers c ON (c.id = iv.customer_id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
      GROUP BY c.name, b.name
      ORDER BY b.name";

   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_biller_by_customer.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setDatabaseInterface("pdo");
   if ($db_server == 'pgsql') {
      $oRpt->setConnection("pgsql:host=$db_host");
   } else {
      $oRpt->setConnection("mysql:host=$db_host");
   }
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

