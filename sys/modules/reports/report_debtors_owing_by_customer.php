<?php
//   include phpreports library
require_once("./include/reportlib.php");

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
         FROM ".TB_PREFIX."payment p GROUP BY p.ac_inv_id
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
        (select coalesce(sum(ii2.total), 0) from ".TB_PREFIX."invoice_items ii2,".TB_PREFIX."invoices iv2 where ii2.invoice_id = iv2.id and iv2.customer_id = c.id) as inv_total,
        (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."payment ap, ".TB_PREFIX."invoices iv3 where ap.ac_inv_id = iv3.id and iv3.customer_id = c.id) as inv_paid,
        (select (inv_total - inv_paid)) as inv_owing

FROM
        ".TB_PREFIX."customers c,".TB_PREFIX."invoices,".TB_PREFIX."invoice_items, ".TB_PREFIX."preferences
WHERE
        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = c.id
        AND ".TB_PREFIX."invoices.preference_id = ".TB_PREFIX."preferences.pref_id
        AND ".TB_PREFIX."preferences.status = 1
GROUP BY
        c.id
HAVING
        inv_owing > 0 
ORDER BY
        inv_owing DESC;
   ";
}

   $oRpt->setXML("./modules/reports/report_debtors_owing_by_customer.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
