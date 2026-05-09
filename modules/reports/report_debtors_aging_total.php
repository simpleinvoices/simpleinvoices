<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

/*
 * Aging totals by bucket and currency - uses si_invoices denorm_amount_* (invoice_denorm).
 */

global $db_server;

$bucket_order = ['0-14' => 0, '15-30' => 1, '31-60' => 2, '61-90' => 3, '90+' => 4];

$aging_sql = '';
switch ($db_server) {
    case 'pgsql':
        $aging_sql = "SELECT bucket, currency_sign,
            SUM(inv_total) AS inv_total,
            SUM(inv_paid) AS inv_paid,
            SUM(owing) AS inv_owing
        FROM (
            SELECT CASE
                WHEN (CURRENT_DATE - iv.date::date) <= 14 THEN '0-14'
                WHEN (CURRENT_DATE - iv.date::date) <= 30 THEN '15-30'
                WHEN (CURRENT_DATE - iv.date::date) <= 60 THEN '31-60'
                WHEN (CURRENT_DATE - iv.date::date) <= 90 THEN '61-90'
                ELSE '90+'
            END AS bucket,
            iv.currency_sign,
            iv.denorm_currency_code,
            iv.denorm_invoice_total AS inv_total,
            iv.denorm_amount_paid AS inv_paid,
            iv.denorm_amount_owing AS owing
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND iv.denorm_amount_owing > 0
        ) x
        GROUP BY bucket, currency_sign, denorm_currency_code";
        break;
    case 'sqlite':
        $aging_sql = "SELECT bucket, currency_sign,
            SUM(inv_total) AS inv_total,
            SUM(inv_paid) AS inv_paid,
            SUM(owing) AS inv_owing
        FROM (
            SELECT CASE
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 14 THEN '0-14'
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 30 THEN '15-30'
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 60 THEN '31-60'
                WHEN CAST((julianday('now') - julianday(date(iv.date))) AS INTEGER) <= 90 THEN '61-90'
                ELSE '90+'
            END AS bucket,
            iv.currency_sign,
            iv.denorm_currency_code,
            iv.denorm_invoice_total AS inv_total,
            iv.denorm_amount_paid AS inv_paid,
            iv.denorm_amount_owing AS owing
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND iv.denorm_amount_owing > 0
        ) x
        GROUP BY bucket, currency_sign, denorm_currency_code";
        break;
    default:
        $aging_sql = "SELECT bucket, currency_sign,
            SUM(inv_total) AS inv_total,
            SUM(inv_paid) AS inv_paid,
            SUM(owing) AS inv_owing
        FROM (
            SELECT CASE
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 14 THEN '0-14'
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 30 THEN '15-30'
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 60 THEN '31-60'
                WHEN DATEDIFF(CURDATE(), DATE(iv.date)) <= 90 THEN '61-90'
                ELSE '90+'
            END AS bucket,
            iv.currency_sign,
            iv.denorm_currency_code,
            iv.denorm_invoice_total AS inv_total,
            iv.denorm_amount_paid AS inv_paid,
            iv.denorm_amount_owing AS owing
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND iv.denorm_amount_owing > 0
        ) x
        GROUP BY bucket, currency_sign, denorm_currency_code";
        break;
}

// Build flat array of rows; sort by bucket order then currency.
$rows = [];
$sum_total = $sum_paid = $sum_owing = 0.0;
foreach (dbQuery($aging_sql, ':domain_id', $auth_session->domain_id)->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $b = $row['bucket'] ?? '';
    if ($b === '') {
        continue;
    }
    $rows[] = [
        'aging'         => $b,
        'currency_sign' => $row['currency_sign'] ?? '',
        'currency_code' => $row['denorm_currency_code'] ?? '',
        'inv_total'     => (float) ($row['inv_total'] ?? 0),
        'inv_paid'      => (float) ($row['inv_paid'] ?? 0),
        'inv_owing'     => (float) ($row['inv_owing'] ?? 0),
    ];
    $sum_total += (float) ($row['inv_total'] ?? 0);
    $sum_paid  += (float) ($row['inv_paid']  ?? 0);
    $sum_owing += (float) ($row['inv_owing'] ?? 0);
}

usort($rows, function ($a, $b) use ($bucket_order) {
    $bo = ($bucket_order[$a['aging']] ?? 99) <=> ($bucket_order[$b['aging']] ?? 99);
    if ($bo !== 0) return $bo;
    $cc = strcmp($a['currency_code'] ?? '', $b['currency_code'] ?? '');
    return $cc !== 0 ? $cc : strcmp($a['currency_sign'] ?? '', $b['currency_sign'] ?? '');
});

$bladeView->assign('data', $rows);
$bladeView->assign('sum_total', $sum_total);
$bladeView->assign('sum_paid', $sum_paid);
$bladeView->assign('sum_owing', $sum_owing);

$bladeView->assign('pageActive', 'report');
$bladeView->assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
