<?php
  $sql = '
SELECT 
    b.name 
  , SUM(lt.line_total) AS sum_total
FROM ' . TB_PREFIX . 'biller b 
    INNER JOIN ' . TB_PREFIX . 'invoices iv ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
    INNER JOIN ' . TB_PREFIX . 'preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)' . si_report_sql_invoice_line_totals_inner_join('iv') . '
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
?>
