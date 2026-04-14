<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

global $db_server;

$domain_id = $auth_session->domain_id;

/**
 * Fast check: at least one enabled row (matches getBillers/getCustomers/getProducts semantics).
 */
function dashboard_has_enabled_row(string $table, string $enabled_col, $domain_id): bool
{
    $sql = 'SELECT 1 FROM ' . TB_PREFIX . $table . ' WHERE domain_id = :domain_id AND (' . $enabled_col . " = 1 OR " . $enabled_col . " = '1') LIMIT 1";
    $r   = dbQuery($sql, ':domain_id', $domain_id)->fetch();
    return (bool) $r;
}

/** Any row in table for domain (tax/preferences include disabled rows). */
function dashboard_has_any_row(string $table, $domain_id): bool
{
    $sql = 'SELECT 1 FROM ' . TB_PREFIX . $table . ' WHERE domain_id = :domain_id LIMIT 1';
    $r   = dbQuery($sql, ':domain_id', $domain_id)->fetch();
    return (bool) $r;
}

$has_billers     = dashboard_has_enabled_row('biller', 'enabled', $domain_id);
$has_customers   = dashboard_has_enabled_row('customers', 'enabled', $domain_id);
$has_products    = dashboard_has_enabled_row('products', 'enabled', $domain_id);
$has_taxes       = dashboard_has_any_row('tax', $domain_id);
$has_preferences = dashboard_has_any_row('preferences', $domain_id);
$has_invoices    = dashboard_has_any_row('invoices', $domain_id);

$first_run_wizard = ! $has_billers || ! $has_customers || ! $has_taxes || ! $has_products || ! $has_preferences;

if ($first_run_wizard) {
    $billers     = getBillers();
    $customers   = getCustomers();
    $taxes       = getTaxes();
    $products    = getProducts();
    $preferences = getPreferences();

    $sample_json = realpath(__DIR__ . '/../../databases/json/sample_data.json');
    $sample_data = ($sample_json && file_exists($sample_json))
        ? json_decode(file_get_contents($sample_json), true)
        : [];
    $bladeView->assign('wizard_sample_biller', $sample_data['si_biller'][0] ?? []);
    $bladeView->assign('wizard_sample_customer', $sample_data['si_customers'][0] ?? []);
    $bladeView->assign('wizard_sample_product', $sample_data['si_products'][0] ?? []);
} else {
    // Avoid loading thousands of rows for dashboard chrome / charts
    $billers = $customers = $products = $taxes = $preferences = [];
}

$defaults = getSystemDefaults();

$bladeView->assign('first_run_wizard', $first_run_wizard);
$bladeView->assign('dash_has_billers', $has_billers);
$bladeView->assign('dash_has_customers', $has_customers);
$bladeView->assign('dash_has_products', $has_products);
$bladeView->assign('dash_has_invoices', $has_invoices);
$bladeView->assign('mysql', $mysql);
$bladeView->assign('db_server', $db_server);
$bladeView->assign('billers', $billers);
$bladeView->assign('customers', $customers);
$bladeView->assign('taxes', $taxes);
$bladeView->assign('products', $products);
$bladeView->assign('preferences', $preferences);
$bladeView->assign('defaults', $defaults);
$bladeView->assign('language', $language);

// ── Dashboard data cache ────────────────────────────────────────────────────
// Expensive chart/aging/payment aggregate queries are cached per domain per
// clock-hour.  Cache expires automatically each new hour; to force a refresh
// delete (or truncate) all files in tmp/cache/dashboard/.
$_dash_cache_dir  = './tmp/cache/dashboard';
$_dash_ttl_bucket = (int) floor(time() / 3600);   // increments once every 60 min
$_dash_cache_file = sprintf('%s/dash_%d_%d.json', $_dash_cache_dir, (int) $domain_id, $_dash_ttl_bucket);
$_dash_from_cache = false;

if (! $first_run_wizard && is_readable($_dash_cache_file)) {
    $_dash_data = json_decode(file_get_contents($_dash_cache_file), true);
    if (is_array($_dash_data)) {
        foreach ($_dash_data as $_k => $_v) {
            $bladeView->assign($_k, $_v);
        }
        $_dash_from_cache = true;
    }
    unset($_dash_data, $_k, $_v);
}

if (! $_dash_from_cache) {

// Dashboard chart: monthly invoices & payments for all years since first invoice (max 10)
$max_chart_years    = 10;
$chart_current_year = (int) date('Y');

$r = dbQuery(
    "SELECT MIN(iv.date) AS min_date FROM " . TB_PREFIX . "invoices iv
     INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
     WHERE pr.status='1' AND iv.domain_id=:domain_id",
    ':domain_id', $domain_id
)->fetch();

$first_invoice_year = ! empty($r['min_date']) ? (int) date('Y', strtotime((string) $r['min_date'])) : $chart_current_year;
$chart_start_year   = ($chart_current_year - $first_invoice_year + 1 > $max_chart_years)
                        ? $chart_current_year - $max_chart_years + 1
                        : $first_invoice_year;

$chart_labels = [];
for ($m = 1; $m <= 12; $m++) {
    $chart_labels[] = date('M', mktime(0, 0, 0, $m, 1));
}

$range_start = sprintf('%04d-01-01', $chart_start_year);
$range_end   = sprintf('%04d-01-01', $chart_current_year + 1);

// Monthly invoice totals & payment totals: two aggregate queries (replaces hundreds of per-month queries)
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
        $domain_id,
        ':d_start',
        $range_start,
        ':d_end',
        $range_end
    )->fetchAll(PDO::FETCH_ASSOC) as $row
) {
    if (! empty($row['ym'])) {
        $inv_by_ym[$row['ym']] = round((float) ($row['t'] ?? 0), 2);
    }
}

$pmt_by_ym = [];
foreach (
    dbQuery(
        $pmt_month_sql,
        ':domain_id',
        $domain_id,
        ':d_start',
        $range_start,
        ':d_end',
        $range_end
    )->fetchAll(PDO::FETCH_ASSOC) as $row
) {
    if (! empty($row['ym'])) {
        $pmt_by_ym[$row['ym']] = round((float) ($row['t'] ?? 0), 2);
    }
}

$chart_years = [];
$chart_data  = [];
for ($y = $chart_start_year; $y <= $chart_current_year; $y++) {
    $chart_years[] = $y;
    $invoices = [];
    $payments = [];
    for ($m = 1; $m <= 12; $m++) {
        $ym = sprintf('%04d-%02d', $y, $m);
        $invoices[] = $inv_by_ym[$ym] ?? 0.0;
        $payments[] = $pmt_by_ym[$ym] ?? 0.0;
    }
    $chart_data[$y] = ['invoices' => $invoices, 'payments' => $payments];
}

// Rolling last 12 calendar months (oldest → newest), for default dashboard chart view
$chart_last12_labels   = [];
$chart_last12_invoices = [];
$chart_last12_payments = [];
try {
    $dash_month_anchor = new DateTime('first day of this month');
} catch (Exception $e) {
    $dash_month_anchor = new DateTime(date('Y-m-01'));
}
for ($i = 11; $i >= 0; $i--) {
    $d                         = (clone $dash_month_anchor)->modify('-' . $i . ' months');
    $ym                        = $d->format('Y-m');
    $chart_last12_labels[]     = $d->format('M Y');
    $chart_last12_invoices[]   = round((float) ($inv_by_ym[$ym] ?? 0), 2);
    $chart_last12_payments[]   = round((float) ($pmt_by_ym[$ym] ?? 0), 2);
}

$bladeView->assign('chart_last12', [
    'labels'   => $chart_last12_labels,
    'invoices' => $chart_last12_invoices,
    'payments' => $chart_last12_payments,
]);
$bladeView->assign('chart_current_year', $chart_current_year);
$bladeView->assign('chart_years', $chart_years);
$bladeView->assign('chart_labels', $chart_labels);
$bladeView->assign('chart_data', $chart_data);

$annual_totals = [];
foreach ($chart_years as $y) {
    $annual_totals[$y] = [
        'invoices' => round(array_sum($chart_data[$y]['invoices']), 2),
        'payments' => round(array_sum($chart_data[$y]['payments']), 2),
    ];
}
$bladeView->assign('annual_totals', $annual_totals);

// Debtor aging buckets — pre-aggregated line totals + payments (one row per invoice; no line-item join fan-out)
$aging_bucket_sql = '';
switch ($db_server) {
    case 'pgsql':
        $aging_bucket_sql = "SELECT bucket, SUM(owing) AS amt FROM (
            SELECT CASE
                WHEN (CURRENT_DATE - iv.date::date) <= 14 THEN '0-14'
                WHEN (CURRENT_DATE - iv.date::date) <= 30 THEN '15-30'
                WHEN (CURRENT_DATE - iv.date::date) <= 60 THEN '31-60'
                WHEN (CURRENT_DATE - iv.date::date) <= 90 THEN '61-90'
                ELSE '90+'
            END AS bucket,
            (COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0)) AS owing
            FROM " . TB_PREFIX . "invoices iv
            LEFT JOIN (
                SELECT invoice_id, domain_id, SUM(COALESCE(total, 0)) AS sum_items
                FROM " . TB_PREFIX . "invoice_items
                GROUP BY invoice_id, domain_id
            ) ii_sum ON (ii_sum.invoice_id = iv.id AND ii_sum.domain_id = iv.domain_id)
            LEFT JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            LEFT JOIN (
                SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
                FROM " . TB_PREFIX . "payment
                GROUP BY ac_inv_id, domain_id
            ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND (COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0)) > 0
        ) x GROUP BY bucket";
        break;
    case 'sqlite':
        $aging_bucket_sql = "SELECT bucket, SUM(owing) AS amt FROM (
            SELECT CASE
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 14 THEN '0-14'
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 30 THEN '15-30'
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 60 THEN '31-60'
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 90 THEN '61-90'
                ELSE '90+'
            END AS bucket,
            (COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0)) AS owing
            FROM " . TB_PREFIX . "invoices iv
            LEFT JOIN (
                SELECT invoice_id, domain_id, SUM(COALESCE(total, 0)) AS sum_items
                FROM " . TB_PREFIX . "invoice_items
                GROUP BY invoice_id, domain_id
            ) ii_sum ON (ii_sum.invoice_id = iv.id AND ii_sum.domain_id = iv.domain_id)
            LEFT JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            LEFT JOIN (
                SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
                FROM " . TB_PREFIX . "payment
                GROUP BY ac_inv_id, domain_id
            ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND (COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0)) > 0
        ) x GROUP BY bucket";
        break;
    default:
        $aging_bucket_sql = "SELECT bucket, SUM(owing) AS amt FROM (
            SELECT CASE
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 14 THEN '0-14'
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 30 THEN '15-30'
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 60 THEN '31-60'
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 90 THEN '61-90'
                ELSE '90+'
            END AS bucket,
            (COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0)) AS owing
            FROM " . TB_PREFIX . "invoices iv
            LEFT JOIN (
                SELECT invoice_id, domain_id, SUM(COALESCE(total, 0)) AS sum_items
                FROM " . TB_PREFIX . "invoice_items
                GROUP BY invoice_id, domain_id
            ) ii_sum ON (ii_sum.invoice_id = iv.id AND ii_sum.domain_id = iv.domain_id)
            LEFT JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            LEFT JOIN (
                SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
                FROM " . TB_PREFIX . "payment
                GROUP BY ac_inv_id, domain_id
            ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND (COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0)) > 0
        ) x GROUP BY bucket";
        break;
}

$aging_buckets = ['0-14' => 0.0, '15-30' => 0.0, '31-60' => 0.0, '61-90' => 0.0, '90+' => 0.0];
$aging_total   = 0.0;
foreach (dbQuery($aging_bucket_sql, ':domain_id', $domain_id)->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $b = $row['bucket'] ?? '';
    if (isset($aging_buckets[$b])) {
        $amt = (float) ($row['amt'] ?? 0);
        $aging_buckets[$b] = $amt;
        $aging_total += $amt;
    }
}

$aging_chart = [];
foreach ($aging_buckets as $label => $amount) {
    $aging_chart[] = [
        'label'   => $label . ' days',
        'amount'  => round($amount, 2),
        'percent' => $aging_total > 0 ? round($amount / $aging_total * 100, 1) : 0,
    ];
}
$bladeView->assign('aging_chart', $aging_chart);
$bladeView->assign('aging_total', round($aging_total, 2));

// Invoice paid percentage — pre-aggregated line totals (matches prior semantics: only invoices with ≥1 line row)
$paid_sql = "SELECT COUNT(*) AS total_count,
    SUM(CASE WHEN owing <= 0 THEN 1 ELSE 0 END) AS paid_count
FROM (
    SELECT iv.id,
        COALESCE(ii_sum.sum_items, 0) - COALESCE(ap.inv_paid, 0) AS owing
    FROM " . TB_PREFIX . "invoices iv
    INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
    INNER JOIN (
        SELECT invoice_id, domain_id, SUM(COALESCE(total, 0)) AS sum_items
        FROM " . TB_PREFIX . "invoice_items
        GROUP BY invoice_id, domain_id
    ) ii_sum ON (ii_sum.invoice_id = iv.id AND ii_sum.domain_id = iv.domain_id)
    LEFT JOIN (
        SELECT ac_inv_id, domain_id, SUM(ac_amount) AS inv_paid
        FROM " . TB_PREFIX . "payment
        GROUP BY ac_inv_id, domain_id
    ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
    WHERE pr.status = 1 AND iv.domain_id = :domain_id
) t";
$paid_row      = dbQuery($paid_sql, ':domain_id', $domain_id)->fetch();
$dash_paid_pct = ($paid_row['total_count'] > 0)
    ? round(($paid_row['paid_count'] ?? 0) / $paid_row['total_count'] * 100, 1) : 0;
$dash_total_inv_count = (int) ($paid_row['total_count'] ?? 0);
$dash_paid_inv_count  = (int) ($paid_row['paid_count'] ?? 0);
$dash_all_invoices_paid = $dash_total_inv_count > 0 && $dash_paid_inv_count >= $dash_total_inv_count;
$dash_aging_all_clear   = $dash_all_invoices_paid && round($aging_total, 2) <= 0;
$bladeView->assign('dash_paid_pct', $dash_paid_pct);
$bladeView->assign('dash_total_inv_count', $dash_total_inv_count);
$bladeView->assign('dash_paid_inv_count', $dash_paid_inv_count);
$bladeView->assign('dash_all_invoices_paid', $dash_all_invoices_paid);
$bladeView->assign('dash_aging_all_clear', $dash_aging_all_clear);

$alltime_inv_monthly = [];
$alltime_pmt_monthly = [];
foreach ($chart_years as $y) {
    foreach ($chart_data[$y]['invoices'] as $v) {
        $alltime_inv_monthly[] = $v;
    }
    foreach ($chart_data[$y]['payments'] as $v) {
        $alltime_pmt_monthly[] = $v;
    }
}
$bladeView->assign('alltime_inv_monthly', $alltime_inv_monthly);
$bladeView->assign('alltime_pmt_monthly', $alltime_pmt_monthly);
$bladeView->assign('dash_alltime_inv_total', round(array_sum($alltime_inv_monthly), 2));
$bladeView->assign('dash_alltime_pmt_total', round(array_sum($alltime_pmt_monthly), 2));

// Monthly volume counts — two aggregate queries
$inv_count_sql = '';
$pmt_count_sql = '';
switch ($db_server) {
    case 'pgsql':
        $inv_count_sql = "SELECT to_char(iv.date::timestamp, 'YYYY-MM') AS ym, COUNT(DISTINCT iv.id) AS c
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = '1' AND iv.domain_id = :domain_id
              AND iv.date >= :d_start AND iv.date < :d_end
            GROUP BY 1";
        $pmt_count_sql = "SELECT to_char(ap.ac_date::timestamp, 'YYYY-MM') AS ym, COUNT(*) AS c
            FROM " . TB_PREFIX . "payment ap
            WHERE ap.domain_id = :domain_id
              AND ap.ac_date >= :d_start AND ap.ac_date < :d_end
            GROUP BY 1";
        break;
    case 'sqlite':
        $inv_count_sql = "SELECT strftime('%Y-%m', iv.date) AS ym, COUNT(DISTINCT iv.id) AS c
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = '1' AND iv.domain_id = :domain_id
              AND date(iv.date) >= date(:d_start) AND date(iv.date) < date(:d_end)
            GROUP BY 1";
        $pmt_count_sql = "SELECT strftime('%Y-%m', ap.ac_date) AS ym, COUNT(*) AS c
            FROM " . TB_PREFIX . "payment ap
            WHERE ap.domain_id = :domain_id
              AND datetime(ap.ac_date) >= datetime(:d_start) AND datetime(ap.ac_date) < datetime(:d_end)
            GROUP BY 1";
        break;
    default:
        $inv_count_sql = "SELECT DATE_FORMAT(iv.date, '%Y-%m') AS ym, COUNT(DISTINCT iv.id) AS c
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = '1' AND iv.domain_id = :domain_id
              AND iv.date >= :d_start AND iv.date < :d_end
            GROUP BY DATE_FORMAT(iv.date, '%Y-%m')";
        $pmt_count_sql = "SELECT DATE_FORMAT(ap.ac_date, '%Y-%m') AS ym, COUNT(*) AS c
            FROM " . TB_PREFIX . "payment ap
            WHERE ap.domain_id = :domain_id
              AND ap.ac_date >= :d_start AND ap.ac_date < :d_end
            GROUP BY DATE_FORMAT(ap.ac_date, '%Y-%m')";
        break;
}

$inv_count_by_ym = [];
foreach (
    dbQuery(
        $inv_count_sql,
        ':domain_id',
        $domain_id,
        ':d_start',
        $range_start,
        ':d_end',
        $range_end
    )->fetchAll(PDO::FETCH_ASSOC) as $row
) {
    if (! empty($row['ym'])) {
        $inv_count_by_ym[$row['ym']] = (int) ($row['c'] ?? 0);
    }
}
$pmt_count_by_ym = [];
foreach (
    dbQuery(
        $pmt_count_sql,
        ':domain_id',
        $domain_id,
        ':d_start',
        $range_start,
        ':d_end',
        $range_end
    )->fetchAll(PDO::FETCH_ASSOC) as $row
) {
    if (! empty($row['ym'])) {
        $pmt_count_by_ym[$row['ym']] = (int) ($row['c'] ?? 0);
    }
}

$alltime_inv_counts = [];
$alltime_pmt_counts = [];
for ($y = $chart_start_year; $y <= $chart_current_year; $y++) {
    for ($m = 1; $m <= 12; $m++) {
        $ym                   = sprintf('%04d-%02d', $y, $m);
        $alltime_inv_counts[] = $inv_count_by_ym[$ym] ?? 0;
        $alltime_pmt_counts[] = $pmt_count_by_ym[$ym] ?? 0;
    }
}
$bladeView->assign('alltime_inv_counts', $alltime_inv_counts);
$bladeView->assign('alltime_pmt_counts', $alltime_pmt_counts);
$bladeView->assign('dash_total_inv_volume', array_sum($alltime_inv_counts));
$bladeView->assign('dash_total_pmt_volume', array_sum($alltime_pmt_counts));

// Latest 5 invoices / payments
$invoice = new invoice();
$invoice->domain_id = $auth_session->domain_id ?? domain_id::get();
if ($auth_session->role_name === 'customer') {
    $invoice->customer = $auth_session->user_id;
} elseif ($auth_session->role_name === 'biller') {
    $invoice->biller = $auth_session->user_id;
}
$invoice->sort = 'iv.id';
$latest_invoices_sth = $invoice->select_all('', 'DESC', 5, 1, '');
$latest_invoices      = $latest_invoices_sth->fetchAll(PDO::FETCH_ASSOC);

switch ($db_server) {
    case 'pgsql':
        $dash_pmt_index_name = "(pr.pref_inv_wording || ' ' || CAST(iv.index_id AS TEXT))";
        $dash_pmt_date       = "TO_CHAR(ap.ac_date, 'YYYY-MM-DD')";
        break;
    case 'sqlite':
        $dash_pmt_index_name = "(pr.pref_inv_wording || ' ' || CAST(iv.index_id AS TEXT))";
        $dash_pmt_date       = "strftime('%Y-%m-%d', ap.ac_date)";
        break;
    default:
        $dash_pmt_index_name = "CONCAT(pr.pref_inv_wording, ' ', iv.index_id)";
        $dash_pmt_date       = "DATE_FORMAT(ap.ac_date,'%Y-%m-%d')";
        break;
}
// Recent payments: same join shape as Manage Payments grid (LEFT JOIN payment_types); order by date then id
$latest_payments_sth = dbQuery(
    "SELECT ap.id, ap.ac_inv_id, ap.ac_amount,
            c.name AS cname,
            b.name AS bname,
            $dash_pmt_index_name AS index_name,
            $dash_pmt_date AS date
     FROM " . TB_PREFIX . "payment ap
     INNER JOIN " . TB_PREFIX . "invoices iv ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "customers c ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "biller b ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = ap.domain_id)
     LEFT JOIN " . TB_PREFIX . "payment_types pt ON (pt.pt_id = ap.ac_payment_type AND pt.domain_id = ap.domain_id)
     WHERE ap.domain_id = :domain_id
     ORDER BY ap.ac_date DESC, ap.id DESC
     LIMIT 5",
    ':domain_id', $domain_id
);
$latest_payments = $latest_payments_sth->fetchAll(PDO::FETCH_ASSOC);

$bladeView->assign('latest_invoices', $latest_invoices);
$bladeView->assign('latest_payments', $latest_payments);

    // ── Persist computed data to cache ─────────────────────────────────────
    if (! $first_run_wizard) {
        $_dash_payload = [
            'chart_last12'           => ['labels' => $chart_last12_labels, 'invoices' => $chart_last12_invoices, 'payments' => $chart_last12_payments],
            'chart_current_year'     => $chart_current_year,
            'chart_years'            => $chart_years,
            'chart_labels'           => $chart_labels,
            'chart_data'             => $chart_data,
            'annual_totals'          => $annual_totals,
            'aging_chart'            => $aging_chart,
            'aging_total'            => $aging_total,
            'dash_paid_pct'          => $dash_paid_pct,
            'dash_total_inv_count'   => $dash_total_inv_count,
            'dash_paid_inv_count'    => $dash_paid_inv_count,
            'dash_all_invoices_paid' => $dash_all_invoices_paid,
            'dash_aging_all_clear'   => $dash_aging_all_clear,
            'alltime_inv_monthly'    => $alltime_inv_monthly,
            'alltime_pmt_monthly'    => $alltime_pmt_monthly,
            'dash_alltime_inv_total' => round(array_sum($alltime_inv_monthly), 2),
            'dash_alltime_pmt_total' => round(array_sum($alltime_pmt_monthly), 2),
            'alltime_inv_counts'     => $alltime_inv_counts,
            'alltime_pmt_counts'     => $alltime_pmt_counts,
            'dash_total_inv_volume'  => array_sum($alltime_inv_counts),
            'dash_total_pmt_volume'  => array_sum($alltime_pmt_counts),
            'latest_invoices'        => $latest_invoices,
            'latest_payments'        => $latest_payments,
        ];
        if (! is_dir($_dash_cache_dir)) {
            @mkdir($_dash_cache_dir, 0755, true);
        }
        @file_put_contents($_dash_cache_file, json_encode($_dash_payload), LOCK_EX);
        // Remove stale cache files for this domain (previous hour buckets)
        foreach (glob($_dash_cache_dir . '/dash_' . (int) $domain_id . '_*.json') ?: [] as $_f) {
            if (realpath($_f) !== realpath($_dash_cache_file)) {
                @unlink($_f);
            }
        }
        unset($_dash_payload, $_f);
    }

} // end if (!$_dash_from_cache)

$bladeView->assign('pageActive', 'dashboard');
$bladeView->assign('active_tab', '#home');
