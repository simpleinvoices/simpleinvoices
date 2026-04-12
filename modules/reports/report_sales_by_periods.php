<?php

/*
* Script: report_sales_by_period.php
* 	Sales reports by period — monthly + annual sales and payments.
*
* Uses a small number of aggregate queries (grouped by year-month) instead of
* one query per month/year, for large databases on MySQL, PostgreSQL, and SQLite.
*/

checkLogin();

global $db_server;

$max_years  = 10;
$this_year  = (int) date('Y');

$sql = "SELECT MIN(iv.date) AS date
	  FROM " . TB_PREFIX . "invoices iv
		INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
	  WHERE pr.status = 1
		AND iv.domain_id = :domain_id";
$sth                = dbQuery($sql, ':domain_id', $auth_session->domain_id);
$invoice_start_array = $sth->fetch();

if (empty($invoice_start_array['date'])) {
    $bladeView->assign('data', [
        'sales'    => ['months' => [], 'months_rate' => [], 'total' => [], 'total_rate' => []],
        'payments' => ['months' => [], 'months_rate' => [], 'total' => [], 'total_rate' => []],
    ]);
    $bladeView->assign('all_years', []);
    $bladeView->assign('chart_years', []);
    $bladeView->assign('report_chart_guard', si_report_chart_allow(0, 0, 1));
    $bladeView->assign('pageActive', 'report');
    $bladeView->assign('active_tab', '#home');
    return;
}

$first_invoice_year = (int) date('Y', strtotime((string) $invoice_start_array['date']));
$year_start_range   = $first_invoice_year;
$total_years        = $this_year - $first_invoice_year + 1;
if ($total_years > $max_years) {
    $year_start_range = $this_year - $max_years + 1;
}

$range_start = sprintf('%04d-01-01', $year_start_range);
$range_end   = sprintf('%04d-01-01', $this_year + 1);

$inv_month_sql = '';
$pmt_month_sql = '';
switch ($db_server) {
    case 'pgsql':
        $inv_month_sql = "SELECT to_char(iv.date::timestamp, 'YYYY-MM') AS ym, SUM(ii.total) AS t
            FROM " . TB_PREFIX . "invoice_items ii
            INNER JOIN " . TB_PREFIX . "invoices iv ON (ii.invoice_id=iv.id AND ii.domain_id=iv.domain_id)
            INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
            WHERE pr.status='1' AND ii.domain_id=:domain_id
              AND iv.date >= :d_start AND iv.date < :d_end
            GROUP BY 1";
        $pmt_month_sql = "SELECT to_char(ap.ac_date::timestamp, 'YYYY-MM') AS ym, SUM(ap.ac_amount) AS t
            FROM " . TB_PREFIX . "payment ap
            WHERE ap.domain_id=:domain_id
              AND ap.ac_date >= :d_start AND ap.ac_date < :d_end
            GROUP BY 1";
        break;
    case 'sqlite':
        $inv_month_sql = "SELECT strftime('%Y-%m', iv.date) AS ym, SUM(ii.total) AS t
            FROM " . TB_PREFIX . "invoice_items ii
            INNER JOIN " . TB_PREFIX . "invoices iv ON (ii.invoice_id=iv.id AND ii.domain_id=iv.domain_id)
            INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
            WHERE pr.status='1' AND ii.domain_id=:domain_id
              AND date(iv.date) >= date(:d_start) AND date(iv.date) < date(:d_end)
            GROUP BY 1";
        $pmt_month_sql = "SELECT strftime('%Y-%m', ap.ac_date) AS ym, SUM(ap.ac_amount) AS t
            FROM " . TB_PREFIX . "payment ap
            WHERE ap.domain_id=:domain_id
              AND datetime(ap.ac_date) >= datetime(:d_start) AND datetime(ap.ac_date) < datetime(:d_end)
            GROUP BY 1";
        break;
    default:
        $inv_month_sql = "SELECT DATE_FORMAT(iv.date, '%Y-%m') AS ym, SUM(ii.total) AS t
            FROM " . TB_PREFIX . "invoice_items ii
            INNER JOIN " . TB_PREFIX . "invoices iv ON (ii.invoice_id=iv.id AND ii.domain_id=iv.domain_id)
            INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
            WHERE pr.status='1' AND ii.domain_id=:domain_id
              AND iv.date >= :d_start AND iv.date < :d_end
            GROUP BY DATE_FORMAT(iv.date, '%Y-%m')";
        $pmt_month_sql = "SELECT DATE_FORMAT(ap.ac_date, '%Y-%m') AS ym, SUM(ap.ac_amount) AS t
            FROM " . TB_PREFIX . "payment ap
            WHERE ap.domain_id=:domain_id
              AND ap.ac_date >= :d_start AND ap.ac_date < :d_end
            GROUP BY DATE_FORMAT(ap.ac_date, '%Y-%m')";
        break;
}

$inv_by_ym = [];
foreach (
    dbQuery(
        $inv_month_sql,
        ':domain_id',
        $auth_session->domain_id,
        ':d_start',
        $range_start,
        ':d_end',
        $range_end
    )->fetchAll(PDO::FETCH_ASSOC) as $row
) {
    if (! empty($row['ym'])) {
        $inv_by_ym[$row['ym']] = (float) ($row['t'] ?? 0);
    }
}

$pmt_by_ym = [];
foreach (
    dbQuery(
        $pmt_month_sql,
        ':domain_id',
        $auth_session->domain_id,
        ':d_start',
        $range_start,
        ':d_end',
        $range_end
    )->fetchAll(PDO::FETCH_ASSOC) as $row
) {
    if (! empty($row['ym'])) {
        $pmt_by_ym[$row['ym']] = (float) ($row['t'] ?? 0);
    }
}

function _myRate($this_year_amount, $last_year_amount, $precision = 2)
{
    if (! $last_year_amount) {
        return '';
    }
    $this_year_amount = (float) $this_year_amount;
    $last_year_amount = (float) $last_year_amount;
    if ($last_year_amount == 0.0) {
        return '';
    }

    return round(($this_year_amount - $last_year_amount) / $last_year_amount * 100, $precision);
}

$years = [];
$data  = [
    'sales'    => ['months' => [], 'months_rate' => [], 'total' => [], 'total_rate' => []],
    'payments' => ['months' => [], 'months_rate' => [], 'total' => [], 'total_rate' => []],
];

for ($m = 1; $m <= 12; $m++) {
    $mk                                      = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
    $data['sales']['months'][$mk]           = [];
    $data['sales']['months_rate'][$mk]      = [];
    $data['payments']['months'][$mk]        = [];
    $data['payments']['months_rate'][$mk]   = [];
}

for ($year = $year_start_range; $year <= $this_year; $year++) {
    array_unshift($years, $year);
    for ($m = 1; $m <= 12; $m++) {
        $mk = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
        $ym = sprintf('%04d-%02d', $year, $m);
        $data['sales']['months'][$mk][$year]    = array_key_exists($ym, $inv_by_ym) ? $inv_by_ym[$ym] : null;
        $data['payments']['months'][$mk][$year] = array_key_exists($ym, $pmt_by_ym) ? $pmt_by_ym[$ym] : null;
    }
}

for ($year = $year_start_range; $year <= $this_year; $year++) {
    $ys = 0.0;
    $yp = 0.0;
    for ($m = 1; $m <= 12; $m++) {
        $mk = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
        $ys += (float) ($data['sales']['months'][$mk][$year] ?? 0);
        $yp += (float) ($data['payments']['months'][$mk][$year] ?? 0);
    }
    $data['sales']['total'][$year]    = $ys != 0.0 ? $ys : null;
    $data['payments']['total'][$year] = $yp != 0.0 ? $yp : null;
}

for ($year = $year_start_range; $year <= $this_year; $year++) {
    $data['sales']['total_rate'][$year]    = _myRate($data['sales']['total'][$year] ?? 0, $data['sales']['total'][$year - 1] ?? 0);
    $data['payments']['total_rate'][$year] = _myRate($data['payments']['total'][$year] ?? 0, $data['payments']['total'][$year - 1] ?? 0);
}

for ($m = 1; $m <= 12; $m++) {
    $mk = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
    for ($year = $year_start_range; $year <= $this_year; $year++) {
        $data['sales']['months_rate'][$mk][$year] = _myRate(
            $data['sales']['months'][$mk][$year] ?? 0,
            $data['sales']['months'][$mk][$year - 1] ?? 0
        );
        $data['payments']['months_rate'][$mk][$year] = _myRate(
            $data['payments']['months'][$mk][$year] ?? 0,
            $data['payments']['months'][$mk][$year - 1] ?? 0
        );
    }
}

$ycount    = count($years);
$inv       = si_report_active_invoice_count($auth_session->domain_id);
$threshold = si_report_chart_allow($inv, 12, max(1, $ycount));
$lim       = si_report_chart_display_limit();
$must      = ! $threshold['enabled'] || $ycount > $lim;
$chart_years = $must ? array_slice($years, 0, min($lim, $ycount)) : $years;
$report_chart_guard = si_report_chart_display_guard($threshold, $ycount, count($chart_years));
$report_chart_guard['chart_time_periods'] = true;

$bladeView->assign('data', $data);
$bladeView->assign('all_years', $years);
$bladeView->assign('chart_years', $chart_years);
$bladeView->assign('report_chart_guard', $report_chart_guard);

$bladeView->assign('pageActive', 'report');
$bladeView->assign('active_tab', '#home');
