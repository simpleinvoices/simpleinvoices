<?php
global $auth_session, $smarty;

$sql = "SELECT
SUM(COALESCE(ii.total, 0)) AS inv_total
, COALESCE(ap.inv_paid, 0) AS inv_paid
, SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing
, (CASE WHEN DATEDIFF(NOW(),DATE) <= '14 days' THEN '0-14'
      WHEN DATEDIFF(NOW(),DATE) <= '30 days' THEN '15-30'
      WHEN DATEDIFF(NOW(),DATE) <= '60 days' THEN '31-60'
      WHEN DATEDIFF(NOW(),DATE) <= '90 days' THEN '61-90'
      ELSE '90+'
  END) AS aging
FROM ".TB_PREFIX."invoice_items ii
LEFT JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
LEFT JOIN ".TB_PREFIX."preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
LEFT JOIN (SELECT ap1.domain_id, SUM(COALESCE(ap1.ac_amount, 0)) AS inv_paid FROM  ".TB_PREFIX."payment ap1
  LEFT JOIN ".TB_PREFIX."invoices iv1 ON (ap1.ac_inv_id = iv1.id AND ap1.domain_id = iv1.domain_id)
  LEFT JOIN ".TB_PREFIX."preferences pr1 ON (pr1.pref_id = iv1.preference_id AND pr1.domain_id = iv1.domain_id)
  WHERE pr1.status = 1 
  GROUP BY ap1.domain_id) ap ON (ap.domain_id = iv.domain_id)
WHERE pr.status   = 1
  AND ii.domain_id = :domain_id
GROUP BY aging;";

$results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

$sum_total = 0;
$sum_paid = 0;
$sum_owing = 0;
$periods = array();
while($period = $results->fetch(PDO::FETCH_ASSOC)) {
    $sum_total += $period['inv_total'];
    $sum_paid  += $period['inv_paid'];
    $sum_owing += $period['inv_owing'];
    array_push($periods, $period);
}

$smarty -> assign('data', $periods);
$smarty -> assign('sum_total', $sum_total);
$smarty -> assign('sum_paid', $sum_paid);
$smarty -> assign('sum_owing', $sum_owing);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
