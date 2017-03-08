<?php
global $auth_session, $smarty;

$sql = "SELECT iv.id, 
               iv.index_id,
               pr.pref_inv_wording,
               b.name AS biller, 
               c.name AS customer, 
               SUM(COALESCE(ii.total, 0)) AS inv_total,
               COALESCE(ap.inv_paid, 0) AS inv_paid,
               SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing,
               `date`
FROM ".TB_PREFIX."invoices iv  
LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id         AND ii.domain_id = iv.domain_id)  
LEFT JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)  
LEFT JOIN ".TB_PREFIX."biller b         ON (iv.biller_id =  b.id          AND  b.domain_id = iv.domain_id)
LEFT JOIN ".TB_PREFIX."customers c      ON (iv.customer_id =  c.id        AND  c.domain_id = iv.domain_id)
LEFT JOIN (SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid 
           FROM ".TB_PREFIX."payment 
           GROUP BY ac_inv_id, domain_id) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
WHERE pr.status = 1
    AND iv.domain_id = :domain_id
GROUP BY iv.id
ORDER BY inv_owing DESC;";

$invoice_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

$total_owed = 0;
$invoices = array();

while($invoice = $invoice_results->fetch()) {
    $total_owed += $invoice['inv_owing'];
    array_push($invoices, $invoice);
}

$smarty -> assign('data', $invoices);
$smarty -> assign('total_owed', $total_owed);   

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
