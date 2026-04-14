<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

  // Per-invoice pre-aggregated lines + payments, then SUM by customer (no c×iv×line fan-out).
  // Payments subquery matches legacy: only amounts on invoices with active (status=1) preference.
  $sql = '
SELECT
        c.id AS cid
      , c.name AS customer
      , SUM(COALESCE(ii_sum.sum_items, 0)) AS inv_total
      , SUM(COALESCE(ap.inv_paid, 0)) AS inv_paid
      , SUM(COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0)) AS inv_owing
FROM
      ' . TB_PREFIX . 'customers c
      INNER JOIN ' . TB_PREFIX . 'invoices iv      ON (iv.customer_id = c.id AND iv.domain_id = c.domain_id)
      INNER JOIN ' . TB_PREFIX . 'preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id AND pr.status = 1)' . si_report_sql_invoice_line_totals_join('iv') . si_report_sql_invoice_payments_join('iv', true) . '
WHERE
          c.domain_id = :domain_id
GROUP BY
	c.id, c.name
ORDER BY
	inv_owing DESC;
';

  $customer_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $total_owed = 0;
  $customers = array();

  while($customer = $customer_results->fetch()) {
    $total_owed += $customer['inv_owing'];
    array_push($customers, $customer);
  }

  $inv = si_report_active_invoice_count($auth_session->domain_id);
  $chart_pack = si_report_chart_top_rows_by_key($customers, 'inv_owing', $inv, 1);

  $bladeView -> assign('data', $customers);
  $bladeView -> assign('report_chart_data', $chart_pack['rows']);
  $bladeView -> assign('total_owed', $total_owed);
  $bladeView -> assign('report_chart_guard', $chart_pack['guard']);

  $bladeView -> assign('pageActive', 'report');
  $bladeView -> assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
?>
