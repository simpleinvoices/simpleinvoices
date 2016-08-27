<?php

	$sql = "SELECT 
			SUM(ii.quantity) AS sum_quantity, c.name, p.description
		FROM ".TB_PREFIX."customers c 
		INNER JOIN ".TB_PREFIX."invoices iv      ON (c.id  = iv.customer_id AND c.domain_id  = iv.domain_id) 
        INNER JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id  AND iv.domain_id = ii.domain_id) 
        INNER JOIN ".TB_PREFIX."products p       ON (p.id  = ii.product_id  AND p.domain_id  = ii.domain_id)
        INNER JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
		WHERE p.visible 
	    AND pr.status = 1
	    AND c.domain_id = :domain_id
		GROUP BY p.description, c.name
		ORDER BY c.name";

	$product_result = dbQuery($sql, ':domain_id', $auth_session->domain_id);

	$customers = array();

	while($product = $product_result->fetch()) {
		$p = array();
		$p['description'] = $product['description'];
		$p['sum_quantity'] = $product['sum_quantity'];

		$customers[$product['name']]['name'] = $product['name'];

		if (!array_key_exists('products', $customers[$product['name']])) {
			$customers[$product['name']]['products'] = array();
		}

		array_push($customers[$product['name']]['products'], $p);

		if (isset($customers[$product['name']]['total_quantity']))	
			$customers[$product['name']]['total_quantity'] += $product['sum_quantity'];
		else
			$customers[$product['name']]['total_quantity'] = $product['sum_quantity'];
	}

	$smarty -> assign('data', $customers);

	$smarty -> assign('pageActive', 'report');
	$smarty -> assign('active_tab', '#home');
