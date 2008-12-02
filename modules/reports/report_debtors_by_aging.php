<?php
//   include phpreports library
require_once("./include/reportlib.php");

   if ($db_server == 'pgsql') {
      $sSQL = "SELECT
        iv.id,
        b.name AS biller,
        c.name AS customer,

        coalesce(ii.total, 0) AS inv_total,
        coalesce(ap.total, 0) AS inv_paid,
        coalesce(ii.total, 0) - coalesce(ap.total, 0) as inv_owing,

        to_char(iv.date,'YYYY-MM-DD') as date,
        age(iv.date) as age,
        (CASE   WHEN age(iv.date) <= '14 days'::interval THEN '0-14'
                WHEN age(iv.date) <= '30 days'::interval THEN '15-30'
                WHEN age(iv.date) <= '60 days'::interval THEN '31-60'
                WHEN age(iv.date) <= '90 days'::interval THEN '61-90'
                ELSE '90+'
        END) as aging

FROM
        ".TB_PREFIX."invoices iv INNER JOIN
	".TB_PREFIX."biller b ON (b.id = iv.biller_id) INNER JOIN
	".TB_PREFIX."customers c ON (c.id = iv.customer_id) LEFT JOIN
        (SELECT i.invoice_id, sum(i.total) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
        (SELECT p.ac_inv_id, sum(p.ac_amount) AS total
         FROM ".TB_PREFIX."payments p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
ORDER BY
        age DESC;
";
   } else {
      $sSQL = "SELECT
        ".TB_PREFIX."invoices.id,
        (select name from ".TB_PREFIX."biller where ".TB_PREFIX."biller.id = ".TB_PREFIX."invoices.biller_id) as biller,
        (select name from ".TB_PREFIX."customers where ".TB_PREFIX."customers.id = ".TB_PREFIX."invoices.customer_id) as customer,
        (select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items WHERE ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id) as inv_total,
        (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."payment where  ac_inv_id = ".TB_PREFIX."invoices.id ) as inv_paid,
        (select (INV_TOTAL - INV_PAID)) as inv_owing ,
        date_format(date,'%Y-%m-%e') as date ,
        (select datediff(now(),date)) as age,
        (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'
                WHEN datediff(now(),date) <= 30 THEN '15-30'
                WHEN datediff(now(),date) <= 60 THEN '31-60'
                WHEN datediff(now(),date) <= 90 THEN '61-90'
                ELSE '90+'
        END ) as aging

FROM
        ".TB_PREFIX."invoices,".TB_PREFIX."payment,".TB_PREFIX."invoice_items, ".TB_PREFIX."biller, ".TB_PREFIX."customers
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id
GROUP BY
        ".TB_PREFIX."invoices.id;

";
}

   $oRpt->setXML("./modules/reports/report_debtors_by_aging.xml");

	//   include phpreports run code
	include("./include/reportrunlib.php");

?>
