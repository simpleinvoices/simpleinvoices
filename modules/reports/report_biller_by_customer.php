<?php

	$sql  = "SELECT 
			SUM(ivt.total) AS SUM_TOTAL, 
			b.name AS Biller, 
			c.name AS Customer 
		FROM 
			".TB_PREFIX."biller b, 
			".TB_PREFIX."customers c, 
			".TB_PREFIX."invoice_items ivt, 
			".TB_PREFIX."invoices iv 
			WHERE iv.customer_id = c.id 
			AND iv.biller_id = b.id 
			AND iv.id = ivt.invoice_id 
			AND iv.domain_id = c.domain_id
			AND iv.domain_id = b.domain_id
			AND iv.domain_id = ivt.domain_id
			AND iv.domain_id = :domain_id
			";
	$sql .= " GROUP BY b.name, c.name";

	$customer_result = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

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
