<?php

/*
* Profit per invoice for a date range - avoids N×M per-invoice/per-product queries.
* Loads line quantities and average inventory costs in bulk (MySQL, PostgreSQL, SQLite).
*/

checkLogin();

function firstOfMonth()
{
    return date('Y-m-d', strtotime('01-' . date('m') . '-' . date('Y') . ' 00:00:00'));
}

function lastOfMonth()
{
    return date('Y-m-d', strtotime('-1 second', strtotime('+1 month', strtotime('01-' . date('m') . '-' . date('Y') . ' 00:00:00'))));
}

isset($_POST['start_date']) ? $start_date = $_POST['start_date'] : $start_date = firstOfMonth();
isset($_POST['end_date']) ? $end_date = $_POST['end_date'] : $end_date = lastOfMonth();

$__rpt_name   = basename(__FILE__, '.php');
$__rpt_params = ['start' => $start_date, 'end' => $end_date];
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id, $__rpt_params)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

$invoice = new invoice();
$invoice->start_date = $start_date;
$invoice->end_date   = $end_date;
$invoice->having     = 'date_between';
$invoice->having_and = 'real';
$invoice_all         = $invoice->select_all();

$invoices = $invoice_all->fetchAll();

$invoice_totals = [
    'sum_total'   => 0,
    'sum_cost'    => 0,
    'sum_profit'  => 0,
];

if (count($invoices) === 0) {
    $bladeView->assign('invoices', $invoices);
    $bladeView->assign('invoice_totals', $invoice_totals);
    $bladeView->assign('start_date', $start_date);
    $bladeView->assign('end_date', $end_date);
    $bladeView->assign('pageActive', 'report');
    $bladeView->assign('active_tab', '#home');
    return;
}

$domain_id = $auth_session->domain_id;
$ids       = array_map('intval', array_column($invoices, 'id'));
$ids       = array_filter($ids, fn ($id) => $id > 0);

// Average cost per product (weighted), one row per product
$avg_cost_sql = 'SELECT product_id,
    SUM(cost * quantity) / NULLIF(SUM(quantity), 0) AS avg_cost
    FROM ' . TB_PREFIX . 'inventory
    WHERE domain_id = :domain_id
    GROUP BY product_id';
$avg_by_product = [];
foreach (dbQuery($avg_cost_sql, ':domain_id', $domain_id)->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $pid = (int) ($row['product_id'] ?? 0);
    if ($pid > 0) {
        $avg_by_product[$pid] = (float) ($row['avg_cost'] ?? 0);
    }
}

// Invoice line quantities: chunk IN (...) for SQLite variable limits (IDs are cast to int)
$items_by_invoice = [];
$chunk_size       = 400;
foreach (array_chunk($ids, $chunk_size) as $chunk) {
    if (count($chunk) === 0) {
        continue;
    }
    $idlist = implode(',', $chunk);
    $sql    = "SELECT invoice_id, product_id, SUM(quantity) AS qty
        FROM " . TB_PREFIX . "invoice_items
        WHERE domain_id = :domain_id AND invoice_id IN ($idlist)
        GROUP BY invoice_id, product_id";
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $iid = (int) $row['invoice_id'];
        if (! isset($items_by_invoice[$iid])) {
            $items_by_invoice[$iid] = [];
        }
        $items_by_invoice[$iid][] = [
            'product_id' => (int) $row['product_id'],
            'qty'        => (float) $row['qty'],
        ];
    }
}

foreach ($invoices as $k => $v) {
    $inv_id           = (int) $v['id'];
    $invoice_total_cost = 0.0;
    foreach ($items_by_invoice[$inv_id] ?? [] as $line) {
        $pid  = $line['product_id'];
        $qty  = $line['qty'];
        $cost = $avg_by_product[$pid] ?? 0.0;
        $invoice_total_cost += $qty * $cost;
    }
    $invoices[$k]['cost']   = $invoice_total_cost;
    $invoices[$k]['profit'] = (float) $v['invoice_total'] - $invoice_total_cost;

    $invoice_totals['sum_total'] += (float) $v['invoice_total'];
    $invoice_totals['sum_cost'] += $invoice_total_cost;
    $invoice_totals['sum_profit'] += $invoices[$k]['profit'];
}

$n_invoices = count($invoices);
$chart_pack = si_report_chart_top_rows_by_key($invoices, 'invoice_total', $n_invoices, 3);

$bladeView->assign('invoices', $invoices);
$bladeView->assign('report_chart_invoices', $chart_pack['rows']);
$bladeView->assign('invoice_totals', $invoice_totals);
$bladeView->assign('start_date', $start_date);
$bladeView->assign('end_date', $end_date);
$bladeView->assign('report_chart_guard', $chart_pack['guard']);

$bladeView->assign('pageActive', 'report');
$bladeView->assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)), $__rpt_params);
