<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";


   $sSQL = "SELECT
        {$tb_prefix}customers.id as CID,
        {$tb_prefix}customers.name as Customer,
        (select IF ( isnull(sum({$tb_prefix}invoice_items.total)), '0', sum({$tb_prefix}invoice_items.total)) from {$tb_prefix}invoice_items,{$tb_prefix}invoices where  {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = CID) as INV_TOTAL,
        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where {$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = CID) as INV_PAID,
        (select (INV_TOTAL - INV_PAID)) as INV_OWING

FROM
        {$tb_prefix}customers,{$tb_prefix}invoices,{$tb_prefix}invoice_items
WHERE
        {$tb_prefix}invoice_items.invoice_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id

GROUP BY
        {$tb_prefix}customers.id
ORDER BY
        INV_OWING DESC;


   ";	
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_owing_by_customer.xml");
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