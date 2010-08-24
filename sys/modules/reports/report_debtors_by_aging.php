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
        iv.id,
        (select name from ".TB_PREFIX."biller where ".TB_PREFIX."biller.id = iv.biller_id) as biller,
        (select name from ".TB_PREFIX."customers where ".TB_PREFIX."customers.id = iv.customer_id) as customer,
        (select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items WHERE ".TB_PREFIX."invoice_items.invoice_id = iv.id) as inv_total,
        -- (select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from ".TB_PREFIX."payment where  ac_inv_id = iv.id ) as inv_paid,
        -- (select (inv_total - inv_paid)) as inv_owing ,

        -- (select coalesce(sum(ii.total), 0) from ".TB_PREFIX."invoice_items ii,".TB_PREFIX."invoices iv where ii.invoice_id = iv.id) as inv_total,
        (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."payment ap, ".TB_PREFIX."invoices iv3 where ap.ac_inv_id = iv.id and iv.customer_id = c.id) as inv_paid,
        (select (inv_total - inv_paid)) as inv_owing,

        date_format(date,'%Y-%m-%e') as date ,
        (select datediff(now(),date)) as age,
        (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'
                WHEN datediff(now(),date) <= 30 THEN '15-30'
                WHEN datediff(now(),date) <= 60 THEN '31-60'
                WHEN datediff(now(),date) <= 90 THEN '61-90'
                ELSE '90+'
        END ) as Aging

FROM
        ".TB_PREFIX."invoices iv, ".TB_PREFIX."biller b, ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_items, ".TB_PREFIX."preferences
WHERE
       ".TB_PREFIX."invoice_items.invoice_id = iv.id
        and iv.customer_id = c.id AND iv.biller_id = b.id 
        AND iv.preference_id = ".TB_PREFIX."preferences.pref_id
        AND ".TB_PREFIX."preferences.status = 1
GROUP BY 
    iv.id
HAVING 
    inv_owing > 0
ORDER BY 
    Aging DESC
        ;
";
}

   $oRpt->setXML("./modules/reports/report_debtors_by_aging.xml");

	//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
