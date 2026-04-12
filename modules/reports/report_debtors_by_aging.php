<?php

/*
 * Debtors by aging — age in days from SQL (no per-row DateTime); bucket from integer age in PHP.
 */

global $db_server;

$age_days_sql = '';
switch ($db_server) {
    case 'pgsql':
        $age_days_sql = '(CURRENT_DATE - iv.date::date)';
        break;
    case 'sqlite':
        $age_days_sql = "CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER)";
        break;
    default:
        $age_days_sql = 'DATEDIFF(CURDATE(), DATE(iv.date))';
        break;
}

$sql = "SELECT
			iv.id,
			iv.index_id,
			pr.pref_inv_wording,
			b.name AS biller,
			c.name AS customer,
			SUM(COALESCE(ii.total, 0)) AS inv_total,
			COALESCE(ap.inv_paid, 0) AS inv_paid,
			SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing,
			iv.date,
			MAX($age_days_sql) AS age_days
		FROM
            " . TB_PREFIX . "invoices iv
            LEFT JOIN " . TB_PREFIX . "invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
            LEFT JOIN " . TB_PREFIX . "biller b         ON (iv.biller_id = b.id AND b.domain_id = iv.domain_id)
            LEFT JOIN " . TB_PREFIX . "customers c      ON (iv.customer_id = c.id AND c.domain_id = iv.domain_id)
            LEFT JOIN " . TB_PREFIX . "preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            LEFT JOIN (
				SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
					FROM " . TB_PREFIX . "payment
					GROUP BY ac_inv_id, domain_id
			) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
		WHERE
				pr.status    = 1
			AND iv.domain_id = :domain_id
		GROUP BY
			iv.id, iv.index_id, iv.date, b.name, c.name, pr.pref_inv_wording, ap.inv_paid
		HAVING
			SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) > 0
		ORDER BY
			iv.date ASC";

$invoice_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

$total_owed = 0;
$periods    = [
    '0-14'  => ['name' => '0-14', 'invoices' => [], 'sum_total' => 0],
    '15-30' => ['name' => '15-30', 'invoices' => [], 'sum_total' => 0],
    '31-60' => ['name' => '31-60', 'invoices' => [], 'sum_total' => 0],
    '61-90' => ['name' => '61-90', 'invoices' => [], 'sum_total' => 0],
    '90+'   => ['name' => '90+', 'invoices' => [], 'sum_total' => 0],
];

while ($invoice = $invoice_results->fetch()) {
    $age = (int) ($invoice['age_days'] ?? 0);
    if ($age <= 14) {
        $bucket = '0-14';
    } elseif ($age <= 30) {
        $bucket = '15-30';
    } elseif ($age <= 60) {
        $bucket = '31-60';
    } elseif ($age <= 90) {
        $bucket = '61-90';
    } else {
        $bucket = '90+';
    }

    $invoice['age']   = $age;
    $invoice['Aging'] = $bucket;

    $periods[$bucket]['invoices'][] = $invoice;
    $periods[$bucket]['sum_total'] += (float) $invoice['inv_owing'];
    $total_owed += (float) $invoice['inv_owing'];
}

$bladeView->assign('data', $periods);
$bladeView->assign('total_owed', $total_owed);

$bladeView->assign('pageActive', 'report');
$bladeView->assign('active_tab', '#home');
