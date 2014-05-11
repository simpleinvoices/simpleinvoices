<?php
  $sql = "
SELECT 
    b.name 
  , SUM(ii.total) AS sum_total
FROM ".TB_PREFIX."biller b 
    INNER JOIN ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
    INNER JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
    INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
WHERE
	    pr.status ='1'
	AND b.domain_id = :domain_id
GROUP BY 
	b.name
";

  $biller_sales = $db->query($sql, ':domain_id', $auth_session->domain_id);

  $total_sales = 0;
  $billers = array();

  while($biller = $biller_sales->fetch()) {
    $total_sales += $biller['sum_total'];
    array_push($billers, $biller);
  }

  $smarty -> assign('data', $billers);
  $smarty -> assign('total_sales', $total_sales);

  $smarty -> assign('pageActive', 'report');
  $smarty -> assign('active_tab', '#home');
?>