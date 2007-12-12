<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "./config/config.php";

   $sSQL = "SELECT sum(ii.quantity) as sum_quantity, c.name, p.description
      FROM ".TB_PREFIX."customers c INNER JOIN
      ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id) INNER JOIN
      ".TB_PREFIX."products p ON (p.id = ii.product_id)
      WHERE p.visible
      GROUP BY p.description, c.name
      ORDER BY c.name";

   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_products_sold_by_customer.xml");
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
