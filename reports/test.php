<?php
   // include the PHPReports classes on the PHP path! configure your path here
   ini_set("include_path",ini_get("include_path").":/var/www/phpreports/"); 
   include "PHPReportMaker.php";

   $sSQL = "select si_biller.b_name, si_customers.c_name, si_invoices.inv_id, si_invoice_items.inv_it_total from si_biller, si_customers, si_invoices, si_invoice_items where si_invoices.inv_id = si_invoice_items.inv_it_invoice_id and si_invoices.inv_biller_id = si_biller.b_id and si_invoices.inv_customer_id = si_customers.c_id
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
			
