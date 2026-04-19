<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());
  $sql = '
SELECT
    b.name
  , SUM(iv.denorm_invoice_total) AS sum_total
FROM ' . TB_PREFIX . 'biller b 
    INNER JOIN ' . TB_PREFIX . 'invoices iv ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
    INNER JOIN ' . TB_PREFIX . 'preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
WHERE
	    pr.status =\'1\'
	AND b.domain_id = :domain_id
GROUP BY 
	b.name
';

  $biller_sales = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $total_sales = 0;
  $billers = array();

  while($biller = $biller_sales->fetch()) {
    $total_sales += $biller['sum_total'];
    array_push($billers, $biller);
  }

  $inv = si_report_active_invoice_count($auth_session->domain_id);
  $chart_pack = si_report_chart_top_rows_by_key($billers, 'sum_total', $inv, 1);

  $bladeView -> assign('data', $billers);
  $bladeView -> assign('report_chart_data', $chart_pack['rows']);
  $bladeView -> assign('total_sales', $total_sales);
  $bladeView -> assign('report_chart_guard', $chart_pack['guard']);

  $bladeView -> assign('pageActive', 'report');
  $bladeView -> assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
?>
