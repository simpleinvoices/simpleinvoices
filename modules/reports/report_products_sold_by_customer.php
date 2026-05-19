<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

	$sql = "SELECT
		  SUM(ii.quantity) AS sum_quantity
		, c.id AS customer_id
		, c.name
		, p.id AS product_id
		, p.description
	FROM ".TB_PREFIX."customers c 
		INNER JOIN ".TB_PREFIX."invoices iv      ON (c.id  = iv.customer_id AND c.domain_id  = iv.domain_id) 
        INNER JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id  AND iv.domain_id = ii.domain_id) 
        INNER JOIN ".TB_PREFIX."products p       ON (p.id  = ii.product_id  AND p.domain_id  = ii.domain_id)
        INNER JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
      WHERE p.visible 
	    AND pr.status = 1
	    AND c.domain_id = :domain_id
      GROUP BY c.id, c.name, p.id, p.description
      ORDER BY c.name";

   $product_result = dbQuery($sql, ':domain_id', $auth_session->domain_id);

   $customers = array();

   while($product = $product_result->fetch()) {
      $p = array();
      $p['description'] = $product['description'];
      $p['sum_quantity'] = $product['sum_quantity'];

      $customers[$product['name']]['name'] = $product['name'];

      if (!array_key_exists('products', $customers[$product['name']])) {
         $customers[$product['name']]['products'] = array();
      }

      array_push($customers[$product['name']]['products'], $p);

      $customers[$product['name']]['total_quantity'] += $product['sum_quantity'];
   }

   $product_series = 0;
   $seen_prod      = [];
   foreach ($customers as $cust) {
	   foreach ($cust['products'] ?? [] as $p) {
		   $d = $p['description'] ?? '';
		   if ($d !== '') {
			   $seen_prod[$d] = true;
		   }
	   }
   }
   $product_series = count($seen_prod);

   $cust_list = array_values($customers);
   usort($cust_list, function ($a, $b) {
	   return (float) ($b['total_quantity'] ?? 0) <=> (float) ($a['total_quantity'] ?? 0);
   });
   $prod_totals = [];
   foreach ($customers as $cust) {
	   foreach ($cust['products'] ?? [] as $p) {
		   $d = $p['description'] ?? '';
		   if ($d === '') {
			   continue;
		   }
		   $prod_totals[$d] = ($prod_totals[$d] ?? 0) + (float) ($p['sum_quantity'] ?? 0);
	   }
   }
   arsort($prod_totals);
   $all_prod = array_keys($prod_totals);
   $nr       = count($cust_list);
   $ns       = count($all_prod);
   $inv  = si_report_active_invoice_count($auth_session->domain_id);
   $omit = si_report_chart_guard_omit_over_invoice_max($inv);
   if ($omit['omit']) {
	   $report_chart_guard = array_merge($omit['guard'], [
		   'chart_row_total'    => $nr,
		   'chart_series_total' => $ns,
	   ]);
	   $slice_r = [];
	   $slice_s = [];
   } else {
	   $t       = si_report_chart_allow($inv, $nr, max(1, $ns));
	   $lim     = si_report_chart_display_limit();
	   $slice_r = (! $t['enabled'] || $nr > $lim) ? array_slice($cust_list, 0, min($lim, $nr)) : $cust_list;
	   $slice_s = (! $t['enabled'] || $ns > $lim) ? array_slice($all_prod, 0, min($lim, $ns)) : $all_prod;

	   $report_chart_guard = array_merge($t, [
		   'threshold_ok'            => ! empty($t['enabled']),
		   'chart_threshold_blocked' => empty($t['enabled']),
		   'enabled'                 => count($slice_r) > 0 && count($slice_s) > 0,
		   'chart_truncated'         => (count($slice_r) < $nr) || (count($slice_s) < $ns),
		   'chart_limit'             => $lim,
		   'chart_matrix'            => true,
		   'chart_row_total'         => $nr,
		   'chart_row_shown'         => count($slice_r),
		   'chart_series_total'      => $ns,
		   'chart_series_shown'      => count($slice_s),
	   ]);
   }

   $bladeView -> assign('data', $customers);
   $bladeView -> assign('report_chart_rows', $slice_r);
   $bladeView -> assign('report_chart_series_names', $slice_s);
   $bladeView -> assign('report_chart_guard', $report_chart_guard);

   $bladeView -> assign('pageActive', 'report');
   $bladeView -> assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
?>