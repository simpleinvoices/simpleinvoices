<?php
   // Single db-agnostic query: aging buckets are computed in PHP below.
   // Verbose GROUP BY satisfies pgsql strict mode and works on MySQL/SQLite too.
   // HAVING uses the full expression (alias references not allowed in pgsql HAVING).
   // Payment subquery groups per invoice (correct for per-invoice owing calculation).
   $sql = "SELECT
        iv.date,
        SUM(COALESCE(ii.total, 0)) AS inv_total,
        COALESCE(ap.inv_paid, 0) AS inv_paid,
        SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing
FROM
        ".TB_PREFIX."invoice_items ii
        LEFT JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
        LEFT JOIN ".TB_PREFIX."preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
        LEFT JOIN (
          SELECT ap1.ac_inv_id, ap1.domain_id, SUM(COALESCE(ap1.ac_amount, 0)) AS inv_paid
          FROM ".TB_PREFIX."payment ap1
              LEFT JOIN ".TB_PREFIX."invoices iv1 ON (ap1.ac_inv_id = iv1.id AND ap1.domain_id = iv1.domain_id)
              LEFT JOIN ".TB_PREFIX."preferences pr1 ON (pr1.pref_id = iv1.preference_id AND pr1.domain_id = iv1.domain_id)
          WHERE pr1.status = 1
          GROUP BY ap1.ac_inv_id, ap1.domain_id
        ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
WHERE
          pr.status = 1
      AND ii.domain_id = :domain_id
GROUP BY
      iv.id, iv.date, ap.inv_paid
HAVING
      SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) > 0
  ";

  $results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $sum_total = 0;
  $sum_paid  = 0;
  $sum_owing = 0;
  $periods   = array();
  $today     = new DateTime();

  while($row = $results->fetch()) {
    $age = (int)$today->diff(new DateTime($row['date']))->days;
    if ($age <= 14)      $bucket = '0-14';
    elseif ($age <= 30)  $bucket = '15-30';
    elseif ($age <= 60)  $bucket = '31-60';
    elseif ($age <= 90)  $bucket = '61-90';
    else                 $bucket = '90+';

    if (!isset($periods[$bucket])) {
        $periods[$bucket] = array('aging' => $bucket, 'inv_total' => 0, 'inv_paid' => 0, 'inv_owing' => 0);
    }
    $periods[$bucket]['inv_total'] += $row['inv_total'];
    $periods[$bucket]['inv_paid']  += $row['inv_paid'];
    $periods[$bucket]['inv_owing'] += $row['inv_owing'];

    $sum_total += $row['inv_total'];
    $sum_paid  += $row['inv_paid'];
    $sum_owing += $row['inv_owing'];
  }

  // Sort buckets oldest-first so the template renders in consistent order.
  $bucket_order = array('0-14' => 0, '15-30' => 1, '31-60' => 2, '61-90' => 3, '90+' => 4);
  uksort($periods, function($a, $b) use ($bucket_order) {
      return $bucket_order[$a] - $bucket_order[$b];
  });

  $smarty -> assign('data', array_values($periods));
  $smarty -> assign('sum_total', $sum_total);
  $smarty -> assign('sum_paid', $sum_paid);
  $smarty -> assign('sum_owing', $sum_owing);

  $smarty -> assign('pageActive', 'report');
  $smarty -> assign('active_tab', '#home');
?>
