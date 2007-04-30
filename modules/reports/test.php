<?php
   // include the PHPReports classes on the PHP path! configure your path here
   ini_set("include_path",ini_get("include_path").":/var/www/phpreports/"); 
   include "PHPReportMaker.php";

   $sSQL = "select {$tb_prefix}biller.name, {$tb_prefix}customers.c_name, {$tb_prefix}invoices.inv_id, {$tb_prefix}invoice_items.inv_it_total from {$tb_prefix}biller, {$tb_prefix}customers, {$tb_prefix}invoices, {$tb_prefix}invoice_items where {$tb_prefix}invoices.inv_id = {$tb_prefix}invoice_items.inv_it_invoice_id and {$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.id and {$tb_prefix}invoices.inv_customer_id = {$tb_prefix}customers.c_id
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
			
