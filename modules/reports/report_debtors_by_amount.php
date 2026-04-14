<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

   // One row per invoice: pre-aggregated line totals + payments (no line-item join fan-out).
   $sql = 'SELECT
      iv.id,
      iv.index_id,
      pr.pref_inv_wording,
      b.name AS biller,
      c.name AS customer,
      COALESCE(ii_sum.sum_items, 0) AS inv_total,
      COALESCE(ap.inv_paid, 0) AS inv_paid,
      COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0) AS inv_owing,
      iv.date
	FROM
        ' . TB_PREFIX . 'invoices iv' . si_report_sql_invoice_line_totals_join('iv') . '
        LEFT JOIN ' . TB_PREFIX . 'preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
        LEFT JOIN ' . TB_PREFIX . 'biller b         ON (iv.biller_id = b.id           AND  b.domain_id = iv.domain_id)
        LEFT JOIN ' . TB_PREFIX . 'customers c      ON (iv.customer_id = c.id         AND  c.domain_id = iv.domain_id)' . si_report_sql_invoice_payments_join('iv', false) . '
	WHERE
		    pr.status = 1
		AND iv.domain_id = :domain_id
	ORDER BY
        inv_owing DESC';

  $invoice_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $total_owed = 0;
  $invoices = array();

  while($invoice = $invoice_results->fetch()) {
    $total_owed += $invoice['inv_owing'];
    array_push($invoices, $invoice);
  }

  $inv = si_report_active_invoice_count($auth_session->domain_id);
  $chart_pack = si_report_chart_top_rows_by_key($invoices, 'inv_owing', $inv, 1);

  $bladeView -> assign('data', $invoices);
  $bladeView -> assign('report_chart_data', $chart_pack['rows']);
  $bladeView -> assign('total_owed', $total_owed);
  $bladeView -> assign('report_chart_guard', $chart_pack['guard']);

  $bladeView -> assign('pageActive', 'report');
  $bladeView -> assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
?>
