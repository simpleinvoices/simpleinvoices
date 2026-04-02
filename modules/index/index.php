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
    $smarty -> assign("first_run_wizard",$first_run_wizard);
}

$smarty -> assign("mysql",$mysql);
$smarty -> assign("db_server",$db_server);
/*
$smarty -> assign("patch",count($patch));
$smarty -> assign("max_patches_applied", $max_patches_applied);
*/
$smarty -> assign("biller", $biller);
$smarty -> assign("billers", $billers);
$smarty -> assign("customer", $customer);
$smarty -> assign("customers", $customers);
$smarty -> assign("taxes", $taxes);
$smarty -> assign("products", $products);
$smarty -> assign("preferences", $preferences);
$smarty -> assign("debtor", $debtor);
$smarty -> assign("language", $language);
//$smarty -> assign("title", $title);

// Dashboard chart: monthly invoices & payments for current and previous year
$chart_current_year = (int)date('Y');
$chart_prev_year    = $chart_current_year - 1;
$chart_labels       = [];
$chart_invoices_cur = [];
$chart_payments_cur = [];
$chart_invoices_prv = [];
$chart_payments_prv = [];

for ($m = 1; $m <= 12; $m++) {
    $mp = str_pad($m, 2, '0', STR_PAD_LEFT);
    $chart_labels[] = date('M', mktime(0, 0, 0, $m, 1));

    // Current year sales
    $r = dbQuery("SELECT SUM(ii.total) AS t FROM " . TB_PREFIX . "invoice_items ii
        INNER JOIN " . TB_PREFIX . "invoices iv ON (ii.invoice_id=iv.id AND iv.domain_id=ii.domain_id)
        INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
        WHERE pr.status='1' AND ii.domain_id=:domain_id AND iv.date LIKE '{$chart_current_year}-{$mp}%'",
        ':domain_id', $auth_session->domain_id)->fetch();
    $chart_invoices_cur[] = round((float)($r['t'] ?? 0), 2);

    // Current year payments
    $r = dbQuery("SELECT SUM(ac_amount) AS t FROM " . TB_PREFIX . "payment
        WHERE domain_id=:domain_id AND ac_date LIKE '{$chart_current_year}-{$mp}%'",
        ':domain_id', $auth_session->domain_id)->fetch();
    $chart_payments_cur[] = round((float)($r['t'] ?? 0), 2);

    // Previous year sales
    $r = dbQuery("SELECT SUM(ii.total) AS t FROM " . TB_PREFIX . "invoice_items ii
        INNER JOIN " . TB_PREFIX . "invoices iv ON (ii.invoice_id=iv.id AND iv.domain_id=ii.domain_id)
        INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id=iv.preference_id AND pr.domain_id=iv.domain_id)
        WHERE pr.status='1' AND ii.domain_id=:domain_id AND iv.date LIKE '{$chart_prev_year}-{$mp}%'",
        ':domain_id', $auth_session->domain_id)->fetch();
    $chart_invoices_prv[] = round((float)($r['t'] ?? 0), 2);

    // Previous year payments
    $r = dbQuery("SELECT SUM(ac_amount) AS t FROM " . TB_PREFIX . "payment
        WHERE domain_id=:domain_id AND ac_date LIKE '{$chart_prev_year}-{$mp}%'",
        ':domain_id', $auth_session->domain_id)->fetch();
    $chart_payments_prv[] = round((float)($r['t'] ?? 0), 2);
}

$smarty->assign('chart_current_year', $chart_current_year);
$smarty->assign('chart_prev_year',    $chart_prev_year);
$smarty->assign('chart_labels',       $chart_labels);
$smarty->assign('chart_invoices_cur', $chart_invoices_cur);
$smarty->assign('chart_payments_cur', $chart_payments_cur);
$smarty->assign('chart_invoices_prv', $chart_invoices_prv);
$smarty->assign('chart_payments_prv', $chart_payments_prv);

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

$smarty->assign('latest_invoices', $latest_invoices);
$smarty->assign('latest_payments', $latest_payments);

$smarty -> assign('pageActive', 'dashboard');
$smarty -> assign('active_tab', '#home');
