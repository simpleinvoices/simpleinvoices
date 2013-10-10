<?php
  $sql = "SELECT SUM(ii.tax_amount) AS sum_tax_total FROM ".TB_PREFIX."invoice_items ii WHERE ii.domain_id = :domain_id";

  $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

  $smarty->assign('total_taxes', $sth->fetchColumn());
	$smarty -> assign('pageActive', 'report');
	$smarty -> assign('active_tab', '#home');
?>