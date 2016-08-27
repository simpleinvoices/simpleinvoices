<?php
  $sql = "
SELECT SUM(ii.tax_amount) AS sum_tax_total 
FROM ".TB_PREFIX."invoice_items ii 
	INNER JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
	INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
WHERE pr.status = 1 AND ii.domain_id = :domain_id
";

  $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $smarty->assign('total_taxes', $sth->fetchColumn());
	$smarty -> assign('pageActive', 'report');
	$smarty -> assign('active_tab', '#home');
?>