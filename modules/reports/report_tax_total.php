<?php
  $sql = "select sum(ii.tax_amount) as sum_tax_total from ".TB_PREFIX."invoice_items ii";

  $sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));

  $smarty->assign('total_taxes', $sth->fetchColumn());
	$smarty -> assign('pageActive', 'report');
	$smarty -> assign('active_tab', '#home');
?>