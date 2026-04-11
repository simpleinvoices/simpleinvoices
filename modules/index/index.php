<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$debtor = getTopDebtor();
$customer = getTopCustomer();
$biller = getTopBiller();

$billers = getBillers();
$customers = getCustomers();
$taxes = getTaxes();
$products = getProducts();
$preferences = getPreferences();
$defaults = getSystemDefaults();

if ($billers == null OR $customers == null OR $taxes == null OR $products == null OR $preferences == null)
{
    $first_run_wizard =true;
    $bladeView -> assign("first_run_wizard",$first_run_wizard);

    // Load sample data for the wizard "Use sample data" prefill buttons
    $sample_json = realpath(__DIR__ . '/../../databases/json/sample_data.json');
    $sample_data = ($sample_json && file_exists($sample_json))
        ? json_decode(file_get_contents($sample_json), true)
        : [];
    $bladeView->assign('wizard_sample_biller',   $sample_data['si_biller'][0]    ?? []);
    $bladeView->assign('wizard_sample_customer',  $sample_data['si_customers'][0] ?? []);
    $bladeView->assign('wizard_sample_product',   $sample_data['si_products'][0]  ?? []);
}

$bladeView -> assign("mysql",$mysql);
$bladeView -> assign("db_server",$db_server);
/*
$bladeView -> assign("patch",count($patch));
$bladeView -> assign("max_patches_applied", $max_patches_applied);
*/
$bladeView -> assign("biller", $biller);
$bladeView -> assign("billers", $billers);
$bladeView -> assign("customer", $customer);
$bladeView -> assign("customers", $customers);
$bladeView -> assign("taxes", $taxes);
$bladeView -> assign("products", $products);
$bladeView -> assign("preferences", $preferences);
$bladeView -> assign("debtor", $debtor);
$bladeView -> assign("language", $language);
//$bladeView -> assign("title", $title);

// Dashboard chart: monthly invoices & payments for all years since first invoice (max 10)
$max_chart_years    = 10;
$chart_current_year = (int)date('Y');

$r = dbQuery(
    "SELECT MIN(iv.date) AS min_date FROM " . TB_PREFIX . "invoices iv
     INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
     WHERE pr.status='1' AND iv.domain_id=:domain_id",
    ':domain_id', $auth_session->domain_id
)->fetch();

$first_invoice_year = !empty($r['min_date']) ? (int)date('Y', strtotime($r['min_date'])) : $chart_current_year;
$chart_start_year   = ($chart_current_year - $first_invoice_year + 1 > $max_chart_years)
                        ? $chart_current_year - $max_chart_years + 1
                        : $first_invoice_year;

$chart_labels = [];
for ($m = 1; $m <= 12; $m++) {
    $chart_labels[] = date('M', mktime(0, 0, 0, $m, 1));
}

$chart_years = [];
$chart_data  = [];
for ($y = $chart_start_year; $y <= $chart_current_year; $y++) {
    $chart_years[] = $y;
    $invoices = [];
    $payments = [];
    for ($m = 1; $m <= 12; $m++) {
        $mp = str_pad($m, 2, '0', STR_PAD_LEFT);

        $month_start = "{$y}-{$mp}-01";
        $month_end   = date('Y-m-d', strtotime("$month_start +1 month"));

        $r = dbQuery("SELECT SUM(ii.total) AS t FROM " . TB_PREFIX . "invoice_items ii
            INNER JOIN " . TB_PREFIX . "invoices iv ON (ii.invoice_id=iv.id AND iv.domain_id=ii.domain_id)
            INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
            WHERE pr.status='1' AND ii.domain_id=:domain_id AND iv.date >= :month_start AND iv.date < :month_end",
            ':domain_id', $auth_session->domain_id, ':month_start', $month_start, ':month_end', $month_end)->fetch();
        $invoices[] = round((float)($r['t'] ?? 0), 2);

        $r = dbQuery("SELECT SUM(ac_amount) AS t FROM " . TB_PREFIX . "payment
            WHERE domain_id=:domain_id AND ac_date >= :month_start AND ac_date < :month_end",
            ':domain_id', $auth_session->domain_id, ':month_start', $month_start, ':month_end', $month_end)->fetch();
        $payments[] = round((float)($r['t'] ?? 0), 2);
    }
    $chart_data[$y] = ['invoices' => $invoices, 'payments' => $payments];
}

$bladeView->assign('chart_current_year', $chart_current_year);
$bladeView->assign('chart_years',        $chart_years);
$bladeView->assign('chart_labels',       $chart_labels);
$bladeView->assign('chart_data',         $chart_data);

// Annual totals for the yearly bar chart (derived from monthly data above)
$annual_totals = [];
foreach ($chart_years as $y) {
    $annual_totals[$y] = [
        'invoices' => round(array_sum($chart_data[$y]['invoices']), 2),
        'payments' => round(array_sum($chart_data[$y]['payments']), 2),
    ];
}
$bladeView->assign('annual_totals', $annual_totals);

// Debtor aging buckets (same logic as report_debtors_by_aging)
$aging_sql = "SELECT
        iv.id, iv.date,
        SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing
    FROM " . TB_PREFIX . "invoices iv
    LEFT JOIN " . TB_PREFIX . "invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
    LEFT JOIN " . TB_PREFIX . "preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
    LEFT JOIN (
        SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
        FROM " . TB_PREFIX . "payment GROUP BY ac_inv_id, domain_id
    ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
    WHERE pr.status = 1 AND iv.domain_id = :domain_id
    GROUP BY iv.id, iv.date, ap.inv_paid
    HAVING SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) > 0";

$aging_result  = dbQuery($aging_sql, ':domain_id', $auth_session->domain_id);
$aging_buckets = ['0-14' => 0, '15-30' => 0, '31-60' => 0, '61-90' => 0, '90+' => 0];
$aging_total   = 0;
$today         = new DateTime();
while ($row = $aging_result->fetch()) {
    $age = (int)$today->diff(new DateTime($row['date']))->days;
    if ($age <= 14)     $bucket = '0-14';
    elseif ($age <= 30) $bucket = '15-30';
    elseif ($age <= 60) $bucket = '31-60';
    elseif ($age <= 90) $bucket = '61-90';
    else                $bucket = '90+';
    $aging_buckets[$bucket] += (float)$row['inv_owing'];
    $aging_total             += (float)$row['inv_owing'];
}
// Convert to percentages for radialBar (0–100), preserve raw totals for tooltips
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

// ── Dashboard stat cards ──────────────────────────────────────────────────────

// 1. Invoice paid percentage
$paid_sql = "SELECT COUNT(*) AS total_count,
    SUM(CASE WHEN owing <= 0 THEN 1 ELSE 0 END) AS paid_count
FROM (
    SELECT iv.id,
        SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS owing
    FROM " . TB_PREFIX . "invoices iv
    INNER JOIN " . TB_PREFIX . "invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
    INNER JOIN " . TB_PREFIX . "preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
    LEFT JOIN (
        SELECT ac_inv_id, domain_id, SUM(ac_amount) AS inv_paid
        FROM " . TB_PREFIX . "payment GROUP BY ac_inv_id, domain_id
    ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
    WHERE pr.status = 1 AND iv.domain_id = :domain_id
    GROUP BY iv.id, ap.inv_paid
) t";
$paid_row      = dbQuery($paid_sql, ':domain_id', $auth_session->domain_id)->fetch();
$dash_paid_pct = ($paid_row['total_count'] > 0)
    ? round(($paid_row['paid_count'] ?? 0) / $paid_row['total_count'] * 100, 1) : 0;
$dash_total_inv_count = (int)($paid_row['total_count'] ?? 0);
$dash_paid_inv_count  = (int)($paid_row['paid_count'] ?? 0);
$dash_all_invoices_paid = $dash_total_inv_count > 0 && $dash_paid_inv_count >= $dash_total_inv_count;
// Aging query only includes owing > 0; combined with all-paid this means a clean receivables picture.
$dash_aging_all_clear   = $dash_all_invoices_paid && round($aging_total, 2) <= 0;
$bladeView->assign('dash_paid_pct',            $dash_paid_pct);
$bladeView->assign('dash_total_inv_count',     $dash_total_inv_count);
$bladeView->assign('dash_paid_inv_count',      $dash_paid_inv_count);
$bladeView->assign('dash_all_invoices_paid',   $dash_all_invoices_paid);
$bladeView->assign('dash_aging_all_clear',     $dash_aging_all_clear);

// 2. All-time monthly amounts — flatten existing chart_data (no extra queries)
$alltime_inv_monthly = [];
$alltime_pmt_monthly = [];
foreach ($chart_years as $y) {
    foreach ($chart_data[$y]['invoices'] as $v) $alltime_inv_monthly[] = $v;
    foreach ($chart_data[$y]['payments'] as $v) $alltime_pmt_monthly[] = $v;
}
$bladeView->assign('alltime_inv_monthly',    $alltime_inv_monthly);
$bladeView->assign('alltime_pmt_monthly',    $alltime_pmt_monthly);
$bladeView->assign('dash_alltime_inv_total', round(array_sum($alltime_inv_monthly), 2));
$bladeView->assign('dash_alltime_pmt_total', round(array_sum($alltime_pmt_monthly), 2));

// 3. All-time monthly volume counts (number of invoices / payments per month)
$alltime_inv_counts = [];
$alltime_pmt_counts = [];
for ($y = $chart_start_year; $y <= $chart_current_year; $y++) {
    for ($m = 1; $m <= 12; $m++) {
        $mp          = str_pad($m, 2, '0', STR_PAD_LEFT);
        $month_start = "{$y}-{$mp}-01";
        $month_end   = date('Y-m-d', strtotime("{$month_start} +1 month"));

        $r = dbQuery(
            "SELECT COUNT(DISTINCT iv.id) AS cnt
             FROM " . TB_PREFIX . "invoices iv
             INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
             WHERE pr.status = '1' AND iv.domain_id = :domain_id
               AND iv.date >= :month_start AND iv.date < :month_end",
            ':domain_id', $auth_session->domain_id, ':month_start', $month_start, ':month_end', $month_end
        )->fetch();
        $alltime_inv_counts[] = (int)($r['cnt'] ?? 0);

        $r = dbQuery(
            "SELECT COUNT(*) AS cnt FROM " . TB_PREFIX . "payment
             WHERE domain_id = :domain_id AND ac_date >= :month_start AND ac_date < :month_end",
            ':domain_id', $auth_session->domain_id, ':month_start', $month_start, ':month_end', $month_end
        )->fetch();
        $alltime_pmt_counts[] = (int)($r['cnt'] ?? 0);
    }
}
$bladeView->assign('alltime_inv_counts',    $alltime_inv_counts);
$bladeView->assign('alltime_pmt_counts',    $alltime_pmt_counts);
$bladeView->assign('dash_total_inv_volume', array_sum($alltime_inv_counts));
$bladeView->assign('dash_total_pmt_volume', array_sum($alltime_pmt_counts));

// Latest 5 invoices
$latest_invoices_sth = dbQuery(
    "SELECT iv.id, iv.index_id, iv.date,
            b.name AS biller, c.name AS customer,
            pr.pref_description AS preference,
            SUM(ii.total) AS invoice_total,
            SUM(ii.total) - COALESCE((SELECT SUM(p.ac_amount) FROM " . TB_PREFIX . "payment p WHERE p.ac_inv_id = iv.id AND p.domain_id = iv.domain_id), 0) AS owing,
            pr.status
     FROM " . TB_PREFIX . "invoices iv
     INNER JOIN " . TB_PREFIX . "invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "biller b ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "customers c ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
     WHERE iv.domain_id = :domain_id
     GROUP BY iv.id, iv.index_id, iv.date, b.name, c.name, pr.pref_description, pr.status
     ORDER BY iv.id DESC
     LIMIT 5",
    ':domain_id', $auth_session->domain_id
);
$latest_invoices = $latest_invoices_sth->fetchAll(PDO::FETCH_ASSOC);

// Latest 5 payments
$latest_payments_sth = dbQuery(
    "SELECT p.id, p.ac_inv_id, p.ac_date, p.ac_amount,
            iv.index_id,
            b.name AS biller, c.name AS customer,
            pr.pref_description AS preference
     FROM " . TB_PREFIX . "payment p
     INNER JOIN " . TB_PREFIX . "invoices iv ON (iv.id = p.ac_inv_id AND iv.domain_id = p.domain_id)
     INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "biller b ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
     INNER JOIN " . TB_PREFIX . "customers c ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
     WHERE p.domain_id = :domain_id
     ORDER BY p.id DESC
     LIMIT 5",
    ':domain_id', $auth_session->domain_id
);
$latest_payments = $latest_payments_sth->fetchAll(PDO::FETCH_ASSOC);

$bladeView->assign('latest_invoices', $latest_invoices);
$bladeView->assign('latest_payments', $latest_payments);

$bladeView -> assign('pageActive', 'dashboard');
$bladeView -> assign('active_tab', '#home');
