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
