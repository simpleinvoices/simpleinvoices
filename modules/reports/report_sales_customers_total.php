<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

  $sql = 'SELECT c.name, iv.currency_sign, iv.currency_code, SUM(iv.denorm_invoice_total) AS sum_total
    FROM
        ' . TB_PREFIX . 'customers c
		INNER JOIN ' . TB_PREFIX . 'invoices iv ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
        INNER JOIN ' . TB_PREFIX . 'preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
    WHERE
           pr.status = \'1\'
       AND c.domain_id = :domain_id
    GROUP BY c.name, iv.currency_sign, iv.currency_code;';

  $customer_sales = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

  $total_sales = 0;
  $customers = array();

  while($customer = $customer_sales->fetch()) {
    $total_sales += $customer['sum_total'];
    array_push($customers, $customer);
  }

  $inv = si_report_active_invoice_count($auth_session->domain_id);
  $omit = si_report_chart_guard_omit_over_invoice_max($inv);
  if ($omit['omit']) {
      $bladeView -> assign('data', $customers);
      $bladeView -> assign('report_chart_data', []);
      $bladeView -> assign('total_sales', $total_sales);
      $bladeView -> assign('report_chart_guard', $omit['guard']);
  } else {
      $chart_pack = si_report_chart_top_rows_by_key($customers, 'sum_total', $inv, 1);
      $bladeView -> assign('data', $customers);
      $bladeView -> assign('report_chart_data', $chart_pack['rows']);
      $bladeView -> assign('total_sales', $total_sales);
      $bladeView -> assign('report_chart_guard', $chart_pack['guard']);
  }

  $bladeView -> assign('pageActive', 'report');
  $bladeView -> assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
?>
