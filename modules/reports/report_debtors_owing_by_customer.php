<?php
global $auth_session, $smarty;

$sql = "SELECT c.id AS cid,
               c.name AS customer,
               SUM(COALESCE(ii.total, 0)) AS inv_total,
               COALESCE(ap.inv_paid, 0) AS inv_paid,
               SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing
        FROM ".TB_PREFIX."customers c 
        LEFT JOIN ".TB_PREFIX."invoices iv      ON (iv.customer_id = c.id AND iv.domain_id = c.domain_id)
        LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
        LEFT JOIN ".TB_PREFIX."preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
        LEFT JOIN (SELECT iv1.customer_id, ap1.domain_id, SUM(COALESCE(ap1.ac_amount, 0)) AS inv_paid 
                   FROM  ".TB_PREFIX."payment ap1
                   LEFT JOIN ".TB_PREFIX."invoices iv1   ON (ap1.ac_inv_id = iv1.id AND ap1.domain_id = iv1.domain_id)
                   LEFT JOIN ".TB_PREFIX."preferences pr1 ON (pr1.pref_id = iv1.preference_id AND pr1.domain_id = iv1.domain_id)
                   WHERE pr1.status = 1
                   GROUP BY iv1.customer_id, ap1.domain_id) ap ON (ap.customer_id = c.id AND ap.domain_id = c.domain_id)
        WHERE pr.status   = 1
          AND c.domain_id = :domain_id
       GROUP BY c.id;";

$customer_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

$total_owed = 0;
$customers = array();

while($customer = $customer_results->fetch()) {
    $total_owed += $customer['inv_owing'];
    array_push($customers, $customer);
}

$smarty -> assign('data', $customers);
$smarty -> assign('total_owed', $total_owed);   

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
