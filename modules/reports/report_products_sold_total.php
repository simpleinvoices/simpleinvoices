<?php

  $inv_count = si_report_active_invoice_count($auth_session->domain_id);

  // Group by product id so distinct products are not merged when descriptions match
  $sql = "
SELECT 
	  p.id AS product_id
	, p.description
	, SUM(ii.quantity) AS sum_quantity
FROM ".TB_PREFIX."invoice_items ii 
	INNER JOIN ".TB_PREFIX."invoices iv    ON (ii.invoice_id = iv.id AND iv.domain_id = ii.domain_id) 
	INNER JOIN ".TB_PREFIX."products p     ON (p.id = ii.product_id  AND p.domain_id = ii.domain_id)
	INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
WHERE 	p.visible 
    AND pr.status = 1
    AND p.domain_id = :domain_id
GROUP BY
	p.id, p.description
";

  $product_sales = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $total_quantity = 0;
  $products = array();

  while ($product = $product_sales->fetch()) {
      $total_quantity += $product['sum_quantity'];
      array_push($products, $product);
  }

  $chart_pack = si_report_chart_top_rows_by_key($products, 'sum_quantity', $inv_count, 1);

  $bladeView->assign('data', $products);
  $bladeView->assign('report_chart_data', $chart_pack['rows']);
  $bladeView->assign('total_quantity', $total_quantity);
  $bladeView->assign('report_chart_guard', $chart_pack['guard']);

  $bladeView->assign('pageActive', 'report');
  $bladeView->assign('active_tab', '#home');
?>