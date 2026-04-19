<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

/*
 * Aging totals by bucket — uses si_invoices denorm_amount_* (invoice_denorm).
 */

global $db_server;

$bucket_order = ['0-14' => 0, '15-30' => 1, '31-60' => 2, '61-90' => 3, '90+' => 4];

$aging_sql = '';
switch ($db_server) {
    case 'pgsql':
        $aging_sql = "SELECT bucket,
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
            iv.denorm_invoice_total AS inv_total,
            iv.denorm_amount_paid AS inv_paid,
            iv.denorm_amount_owing AS owing
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND iv.denorm_amount_owing > 0
        ) x
        GROUP BY bucket";
        break;
    case 'sqlite':
        $aging_sql = "SELECT bucket,
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
            iv.denorm_invoice_total AS inv_total,
            iv.denorm_amount_paid AS inv_paid,
            iv.denorm_amount_owing AS owing
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND iv.denorm_amount_owing > 0
        ) x
        GROUP BY bucket";
        break;
    default:
        $aging_sql = "SELECT bucket,
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
            iv.denorm_invoice_total AS inv_total,
            iv.denorm_amount_paid AS inv_paid,
            iv.denorm_amount_owing AS owing
            FROM " . TB_PREFIX . "invoices iv
            INNER JOIN " . TB_PREFIX . "preferences pr ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND iv.domain_id = :domain_id
              AND iv.denorm_amount_owing > 0
        ) x
        GROUP BY bucket";
        break;
}

$by_bucket = [];
foreach (dbQuery($aging_sql, ':domain_id', $auth_session->domain_id)->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $b = $row['bucket'] ?? '';
    if ($b !== '') {
        $by_bucket[$b] = [
            'inv_total' => (float) ($row['inv_total'] ?? 0),
            'inv_paid'  => (float) ($row['inv_paid'] ?? 0),
            'inv_owing' => (float) ($row['inv_owing'] ?? 0),
        ];
    }
}

$sum_total = $sum_paid = $sum_owing = 0.0;
$periods   = [];
foreach (array_keys($bucket_order) as $b) {
    $r = $by_bucket[$b] ?? ['inv_total' => 0, 'inv_paid' => 0, 'inv_owing' => 0];
    $periods[$b] = [
        'aging'     => $b,
        'inv_total' => $r['inv_total'],
        'inv_paid'  => $r['inv_paid'],
        'inv_owing' => $r['inv_owing'],
    ];
    $sum_total += $r['inv_total'];
    $sum_paid += $r['inv_paid'];
    $sum_owing += $r['inv_owing'];
}

uksort($periods, function ($a, $b) use ($bucket_order) {
    return $bucket_order[$a] - $bucket_order[$b];
});

$bladeView->assign('data', array_values($periods));
$bladeView->assign('sum_total', $sum_total);
$bladeView->assign('sum_paid', $sum_paid);
$bladeView->assign('sum_owing', $sum_owing);

$bladeView->assign('pageActive', 'report');
$bladeView->assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
