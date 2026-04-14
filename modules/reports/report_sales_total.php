<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());
    global $db_server;

    // pgsql uses STRING_AGG; MySQL and SQLite both use GROUP_CONCAT with ',' as
    // the default separator, so no SEPARATOR clause is needed.
    $agg = ($db_server == 'pgsql')
        ? "STRING_AGG(DISTINCT pr.pref_description, ',')"
        : "GROUP_CONCAT(DISTINCT pr.pref_description)";

    $t = TB_PREFIX;

    $sql = "SELECT
              pr.index_group AS grp
            , $agg AS template
            , COUNT(*) AS count
            , SUM(per_inv.inv_sum) AS sum_total
    FROM (
        SELECT iv.id, iv.preference_id, iv.domain_id,
            SUM(COALESCE(ii.total, 0)) AS inv_sum
        FROM {$t}invoices iv
        INNER JOIN {$t}invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
        INNER JOIN {$t}preferences pr2 ON (pr2.pref_id = iv.preference_id AND pr2.domain_id = iv.domain_id)
        WHERE pr2.status = '1'
          AND ii.domain_id = :domain_id
        GROUP BY iv.id, iv.preference_id, iv.domain_id
    ) per_inv
    INNER JOIN {$t}preferences pr ON (pr.pref_id = per_inv.preference_id AND pr.domain_id = per_inv.domain_id)
    GROUP BY pr.index_group
    ";

    $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

    $grand_total_sales = 0;
	$total_sales = Array();

    while($sales = $sth->fetch()) {
		$grand_total_sales += $sales['sum_total'];
		array_push($total_sales, $sales);
  }

//    $bladeView->assign('total_sales', $sth->fetchColumn());
    $inv = si_report_active_invoice_count($auth_session->domain_id);
    $chart_pack = si_report_chart_top_rows_by_key($total_sales, 'sum_total', $inv, 1);
    $bladeView ->assign('data', $total_sales);
    $bladeView ->assign('report_chart_data', $chart_pack['rows']);
    $bladeView ->assign('report_chart_guard', $chart_pack['guard']);
    $bladeView ->assign('grand_total_sales', $grand_total_sales);
    $bladeView -> assign('pageActive', 'report_sale');
    $bladeView -> assign('active_tab', '#money');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
?>
