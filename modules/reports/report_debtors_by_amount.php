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
        coalesce(ii.total, 0) - coalesce(ap.total, 0) AS inv_owing,
        iv.date
FROM
        ".TB_PREFIX."invoices iv INNER JOIN
	".TB_PREFIX."customers c ON (c.id = iv.customer_id) INNER JOIN
	".TB_PREFIX."biller b ON (b.id = iv.biller_id) LEFT JOIN
        (SELECT i.invoice_id, sum(i.total) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
        (SELECT p.ac_inv_id, sum(p.ac_amount) AS total
         FROM ".TB_PREFIX."account_payments p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
ORDER BY
        inv_owing DESC;
";
   } else {
      $sSQL = "SELECT
        iv.id,
        (select name from ".TB_PREFIX."biller where ".TB_PREFIX."biller.id = iv.biller_id) as biller,
        (select name from ".TB_PREFIX."customers where id = iv.customer_id) as customer,
        (select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items WHERE ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id) as inv_total,
        ( select coalesce ( sum(ac_amount), 0) from ".TB_PREFIX."account_payments where  ac_inv_id = ".TB_PREFIX."invoices.id ) as inv_paid,
        (select (INV_TOTAL - INV_PAID)) as inv_owing ,
        date
FROM
        ".TB_PREFIX."invoices iv INNER JOIN
        ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id),
	".TB_PREFIX."account_payments,".TB_PREFIX."biller, ".TB_PREFIX."customers
GROUP BY
        iv.id
ORDER BY
        inv_owing DESC;

";
   }

   $oRpt->setXML("./modules/reports/report_debtors_by_amount.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>
