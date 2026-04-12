<?php
    global $db_server;

    // pgsql uses STRING_AGG; MySQL and SQLite both use GROUP_CONCAT with ',' as
    // the default separator, so no SEPARATOR clause is needed.
    $agg = ($db_server == 'pgsql')
        ? "STRING_AGG(DISTINCT pr.pref_description, ',')"
        : "GROUP_CONCAT(DISTINCT pr.pref_description)";

    $sql = "SELECT
              pr.index_group AS grp
            , $agg AS template
            , COUNT(DISTINCT ii.invoice_id) AS count
            , SUM(ii.total) AS sum_total
    FROM
        ".TB_PREFIX."invoice_items ii
        INNER JOIN ".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
        INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
    WHERE
           pr.status = '1'
       AND ii.domain_id = :domain_id
    GROUP BY
        pr.index_group
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
?>
