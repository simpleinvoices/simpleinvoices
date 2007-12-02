<?php
   // include the PHPReports classes on the PHP path! configure your path here
   ini_set("include_path",ini_get("include_path").":/var/www/phpreports/"); 
   include "PHPReportMaker.php";

   $sSQL = "SELECT b.name, c.name, iv.id, ii.total
   FROM ".TB_PREFIX."biller b INNER JOIN
      ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id) INNER JOIN
      ".TB_PREFIX."customers c ON (c.id = iv.customer_id) INNER JOIN
      ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id)
";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("test.xml");
   $oRpt->setUser("php");
   $oRpt->setPassword("php");
   $oRpt->setConnection("localhost");
   global $db_server;
   if ($db_server == 'pgsql') {
      $oRpt->setDatabaseInterface("postgresql");
   } else {
      $oRpt->setDatabaseInterface("mysql");
   }
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("simple_invoices");
   $oRpt->run();
?>
			
