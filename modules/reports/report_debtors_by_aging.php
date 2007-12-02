<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./modules/reports/PHPReportMaker.php";

   if ($db_server == 'pgsql') {
      $sSQL = "SELECT
        iv.id,
        b.name AS \"Biller\",
        c.name AS \"Customer\",

        coalesce(ii.total, 0) AS \"INV_TOTAL\",
        coalesce(ap.total, 0) AS \"INV_PAID\",
        coalesce(ii.total, 0) - coalesce(ap.total, 0) as \"INV_OWING\",

        to_char(iv.date,'YYYY-MM-DD') as \"Date\",
        age(iv.date) as \"Age\",
        (CASE   WHEN age(iv.date) <= 14 THEN '0-14'
                WHEN age(iv.date) <= 30 THEN '15-30'
                WHEN age(iv.date) <= 60 THEN '31-60'
                WHEN age(iv.date) <= 90 THEN '61-90'
                ELSE '90+'
        END) as \"Aging\"

FROM
        ".TB_PREFIX."invoices iv INNER JOIN
	".TB_PREFIX."biller b ON (b.id = iv.biller_id) INNER JOIN
	".TB_PREFIX."customers c ON (c.id = iv.customer_id) LEFT JOIN
        (SELECT i.invoice_id, sum(i.total) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
        (SELECT p.ac_inv_id, sum(p.ac_amount) AS total
         FROM ".TB_PREFIX."account_payments p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
ORDER BY
        \"Age\" DESC;
";
   } else {
      $sSQL = "SELECT
        ".TB_PREFIX."invoices.id,
        (select name from ".TB_PREFIX."biller where ".TB_PREFIX."biller.id = ".TB_PREFIX."invoices.biller_id) as Biller,
        (select name from ".TB_PREFIX."customers where ".TB_PREFIX."customers.id = ".TB_PREFIX."invoices.customer_id) as Customer,
        (select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items WHERE ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id) as INV_TOTAL,
        (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."account_payments where  ac_inv_id = ".TB_PREFIX."invoices.id ) as INV_PAID,
        (select (INV_TOTAL - INV_PAID)) as INV_OWING ,
        date_format(date,'%Y-%m-%e') as Date ,
        (select datediff(now(),date)) as Age,
        (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'
                WHEN datediff(now(),date) <= 30 THEN '15-30'
                WHEN datediff(now(),date) <= 60 THEN '31-60'
                WHEN datediff(now(),date) <= 90 THEN '61-90'
                ELSE '90+'
        END ) as Aging

FROM
        ".TB_PREFIX."invoices,".TB_PREFIX."account_payments,".TB_PREFIX."invoice_items, ".TB_PREFIX."biller, ".TB_PREFIX."customers
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id
GROUP BY
        ".TB_PREFIX."invoices.id;

";
   }
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("./modules/reports/xml/report_debtors_by_aging.xml");
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
