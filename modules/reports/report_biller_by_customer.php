<?php

  $sql = "
SELECT 
      b.name  AS Biller
	, c.name AS Customer 
	, SUM(ii.total) AS SUM_TOTAL
FROM ".TB_PREFIX."biller b 
    INNER JOIN ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
    INNER JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
    INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
	INNER JOIN ".TB_PREFIX."customers c ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
WHERE
	    pr.status ='1'
	AND b.domain_id = :domain_id
GROUP BY 
	b.name, c.name
";

	$customer_result = dbQuery($sql, ':domain_id', domain_id::get());

	$billers = array();
	$total_sales = 0;

	while($customer = $customer_result->fetch()) {
	  $c = array();
	  $c['name'] = $customer['Customer'];
	  $c['sum_total'] = $customer['SUM_TOTAL'];

	  $billers[$customer['Biller']]['name'] = $customer['Biller'];

	  if (!array_key_exists('customers', $billers[$customer['Biller']])) {
	     $billers[$customer['Biller']]['customers'] = array();
	  }

	  array_push($billers[$customer['Biller']]['customers'], $c);

	  $billers[$customer['Biller']]['total_sales'] += $customer['SUM_TOTAL'];

	  $total_sales += $customer['SUM_TOTAL'];
	}

	$smarty -> assign('data', $billers);
	$smarty -> assign('total_sales', $total_sales);

	$smarty -> assign('pageActive', 'report');
	$smarty -> assign('active_tab', '#home');
?>
