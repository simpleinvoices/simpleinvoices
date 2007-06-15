<?php
   // include the PHPReports classes on the PHP path! configure your path here
   ini_set("include_path",ini_get("include_path").":/var/www/phpreports/"); 
   include "PHPReportMaker.php";

   $sSQL = "select ".TB_PREFIX."biller.name, ".TB_PREFIX."customers.c_name, ".TB_PREFIX."invoices.inv_id, ".TB_PREFIX."invoice_items.inv_it_total from ".TB_PREFIX."biller, ".TB_PREFIX."customers, ".TB_PREFIX."invoices, ".TB_PREFIX."invoice_items where ".TB_PREFIX."invoices.inv_id = ".TB_PREFIX."invoice_items.inv_it_invoice_id and ".TB_PREFIX."invoices.inv_biller_id = ".TB_PREFIX."biller.id and ".TB_PREFIX."invoices.inv_customer_id = ".TB_PREFIX."customers.c_id
";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("test.xml");
   $oRpt->setUser("php");
   $oRpt->setPassword("php");
   $oRpt->setConnection("localhost");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("simple_invoices");
   $oRpt->run();
?>
			
