<?php

  $sql = '
SELECT 
      b.name  AS Biller
	, c.name AS Customer 
	, SUM(lt.line_total) AS SUM_TOTAL
FROM ' . TB_PREFIX . 'biller b 
    INNER JOIN ' . TB_PREFIX . 'invoices iv ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
    INNER JOIN ' . TB_PREFIX . 'preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)' . si_report_sql_invoice_line_totals_inner_join('iv') . '
	INNER JOIN ' . TB_PREFIX . 'customers c ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
WHERE
	    pr.status =\'1\'
	AND b.domain_id = :domain_id
GROUP BY 
	b.name, c.name
';

	$customer_result = dbQuery($sql, ':domain_id', $auth_session->domain_id);

	$billers = array();
	$total_sales = 0;

	while($customer = $customer_result->fetch()) {
	  $c = array();
	  $c['name'] = $customer['Customer'];
	  $c['sum_total'] = $customer['SUM_TOTAL'];

	  $billers[$customer['Biller']]['name'] = $customer['Biller'];

	  if (!array_key_exists('customers', $billers[$customer['Biller']])) {
	     $billers[$customer['Biller']]['customers'] = array();
	  }

	  array_push($billers[$customer['Biller']]['customers'], $c);

	  $billers[$customer['Biller']]['total_sales'] += $customer['SUM_TOTAL'];

	  $total_sales += $customer['SUM_TOTAL'];
	}

	$seen_cust = [];
	foreach ($billers as $b) {
		foreach ($b['customers'] ?? [] as $c) {
			$n = $c['name'] ?? '';
			if ($n !== '') {
				$seen_cust[$n] = true;
			}
		}
	}
	$customer_series = count($seen_cust);

	$biller_list = array_values($billers);
	usort($biller_list, function ($a, $b) {
		return (float) ($b['total_sales'] ?? 0) <=> (float) ($a['total_sales'] ?? 0);
	});
	$cust_totals = [];
	foreach ($billers as $b) {
		foreach ($b['customers'] ?? [] as $c) {
			$n = $c['name'] ?? '';
			if ($n === '') {
				continue;
			}
			$cust_totals[$n] = ($cust_totals[$n] ?? 0) + (float) ($c['sum_total'] ?? 0);
		}
	}
	arsort($cust_totals);
	$all_cust = array_keys($cust_totals);
	$nb       = count($biller_list);
	$nc       = count($all_cust);
	$inv = si_report_active_invoice_count($auth_session->domain_id);
	$omit = si_report_chart_guard_omit_over_invoice_max($inv);
	if ($omit['omit']) {
		$report_chart_guard = array_merge($omit['guard'], [
			'chart_row_total'    => $nb,
			'chart_series_total' => $nc,
		]);
		$slice_b = [];
		$slice_c = [];
	} else {
		$t       = si_report_chart_allow($inv, $nb, max(1, $nc));
		$lim     = si_report_chart_display_limit();
		$slice_b = (! $t['enabled'] || $nb > $lim) ? array_slice($biller_list, 0, min($lim, $nb)) : $biller_list;
		$slice_c = (! $t['enabled'] || $nc > $lim) ? array_slice($all_cust, 0, min($lim, $nc)) : $all_cust;

		$report_chart_guard = array_merge($t, [
			'threshold_ok'            => ! empty($t['enabled']),
			'chart_threshold_blocked' => empty($t['enabled']),
			'enabled'                 => count($slice_b) > 0 && count($slice_c) > 0,
			'chart_truncated'         => (count($slice_b) < $nb) || (count($slice_c) < $nc),
			'chart_limit'             => $lim,
			'chart_matrix'            => true,
			'chart_row_total'         => $nb,
			'chart_row_shown'         => count($slice_b),
			'chart_series_total'      => $nc,
			'chart_series_shown'      => count($slice_c),
		]);
	}

	$bladeView -> assign('data', $billers);
	$bladeView -> assign('report_chart_billers', $slice_b);
	$bladeView -> assign('report_chart_series_names', $slice_c);
	$bladeView -> assign('total_sales', $total_sales);
	$bladeView -> assign('report_chart_guard', $report_chart_guard);

	$bladeView -> assign('pageActive', 'report');
	$bladeView -> assign('active_tab', '#home');
?>
