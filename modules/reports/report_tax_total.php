<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());

$sql = "
SELECT iv.currency_sign, iv.currency_code, SUM(ii.tax_amount) AS sum_tax_total
FROM ".TB_PREFIX."invoice_items ii
	INNER JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
	INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
WHERE pr.status = 1 AND ii.domain_id = :domain_id
GROUP BY iv.currency_sign, iv.currency_code
ORDER BY iv.currency_code, iv.currency_sign
";

$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

$taxes = [];
$total_taxes = 0.0;
foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $taxes[] = [
        'currency_sign' => $row['currency_sign'] ?? '',
        'currency_code' => $row['currency_code'] ?? '',
        'sum_tax_total' => (float) ($row['sum_tax_total'] ?? 0),
    ];
    $total_taxes += (float) ($row['sum_tax_total'] ?? 0);
}

$bladeView->assign('taxes', $taxes);
$bladeView->assign('total_taxes', $total_taxes);
$bladeView->assign('pageActive', 'report');
$bladeView->assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
