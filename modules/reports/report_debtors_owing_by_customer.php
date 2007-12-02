<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "config/config.php";


   if ($db_server == 'pgsql') {
      $sSQL = "SELECT
        c.id AS \"CID\",
        c.name AS \"Customer\",
        sum(coalesce(ii.total, 0)) AS \"INV_TOTAL\",
        sum(coalesce(ap.ac_amount, 0)) AS \"INV_PAID\",
        sum(coalesce(ii.total, 0)) -
        sum(coalesce(ap.ac_amount, 0)) AS \"INV_OWING\"

FROM
        ".TB_PREFIX."customers c LEFT JOIN
        ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) LEFT JOIN
	(SELECT i.invoice_id, coalesce(sum(i.total), 0) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id)
	(SELECT p.ac_inv_id, coalesce(sum(p.ac_amount), 0) AS ac_amount
         FROM ".TB_PREFIX."account_payments p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
GROUP BY
        c.id, c.name
ORDER BY
        \"INV_OWING\" DESC;
   ";
   } else {
      $sSQL = "SELECT
        c.id as CID,
        c.name as Customer,
        (select coalesce(sum(ii.total), 0) from ".TB_PREFIX."invoice_items,".TB_PREFIX."invoices where ii2.invoice_id = iv2.id and iv2.customer_id = c.id) as INV_TOTAL,
        (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv2 where ap.ac_inv_id = iv2.id and iv2.customer_id = c.id) as INV_PAID,
        (select (INV_TOTAL - INV_PAID)) as INV_OWING

FROM
        ".TB_PREFIX."customers,".TB_PREFIX."invoices,".TB_PREFIX."invoice_items
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id

GROUP BY
        ".TB_PREFIX."customers.id
ORDER BY
        INV_OWING DESC;
   ";
   }
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_owing_by_customer.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   if ($db_server == 'pgsql') {
      $oRpt->setDatabaseInterface("postgresql");
   } else {
      $oRpt->setDatabaseInterface("mysql");
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
