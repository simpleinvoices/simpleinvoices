<?php
   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";
   include "./config/config.php";


   if ($db_server == 'pgsql') {
      $sSQL = "SELECT
        c.id AS cid,
        c.name AS customer,
        sum(coalesce(ii.total, 0)) AS inv_total,
        sum(coalesce(ap.ac_amount, 0)) AS inv_paid,
        sum(coalesce(ii.total, 0)) -
        sum(coalesce(ap.ac_amount, 0)) AS inv_owing

FROM
        ".TB_PREFIX."customers c LEFT JOIN
        ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) LEFT JOIN
	(SELECT i.invoice_id, coalesce(sum(i.total), 0) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
	(SELECT p.ac_inv_id, coalesce(sum(p.ac_amount), 0) AS ac_amount
         FROM ".TB_PREFIX."account_payments p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
GROUP BY
        c.id, c.name
ORDER BY
        inv_owing DESC;
   ";
   } else {
      $sSQL = "SELECT
        c.id as cid,
        c.name as customer,
        (select coalesce(sum(ii.total), 0) from ".TB_PREFIX."invoice_items,".TB_PREFIX."invoices where ii2.invoice_id = iv2.id and iv2.customer_id = c.id) as inv_total,
        (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv2 where ap.ac_inv_id = iv2.id and iv2.customer_id = c.id) as inv_paid,
        (select (inv_total - inv_paid)) as inv_owing

FROM
        ".TB_PREFIX."customers,".TB_PREFIX."invoices,".TB_PREFIX."invoice_items
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id

GROUP BY
        ".TB_PREFIX."customers.id
ORDER BY
        inv_owing DESC;
   ";
   }
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_owing_by_customer.xml");
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
