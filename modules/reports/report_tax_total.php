<?php
$__rpt_name = basename(__FILE__, '.php');
if (($__rpt = report_cache_get($__rpt_name, (int)$auth_session->domain_id)) !== null) { foreach ($__rpt as $k => $v) $bladeView->assign($k, $v); return; }
$__rpt_snap = array_keys($bladeView->getAssigns());
  $sql = "
SELECT SUM(ii.tax_amount) AS sum_tax_total 
FROM ".TB_PREFIX."invoice_items ii 
	INNER JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
	INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
WHERE pr.status = 1 AND ii.domain_id = :domain_id
";

  $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $bladeView->assign('total_taxes', $sth->fetchColumn());
	$bladeView -> assign('pageActive', 'report');
	$bladeView -> assign('active_tab', '#home');
report_cache_set($__rpt_name, (int)$auth_session->domain_id,
    array_diff_key($bladeView->getAssigns(), array_flip($__rpt_snap)));
?>