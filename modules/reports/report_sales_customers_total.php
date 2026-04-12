<?php 

  $sql = "SELECT c.name, SUM(ii.total) AS sum_total
    FROM 
        ".TB_PREFIX."customers c
		INNER JOIN ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id) 
        INNER JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id) 
        INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id) 
    WHERE
           pr.status = '1'
       AND c.domain_id = :domain_id
    GROUP BY c.name;";

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
?>
