<?php 

  $sql = "SELECT c.name, SUM(ii.total) AS sum_total
    FROM 
        ".TB_PREFIX."customers c
		INNER JOIN ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id) 
        INNER JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id) 
        INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id) 
    WHERE
           pr.status = '1'
       AND c.domain_id = :domain_id
    GROUP BY c.name;";

  $customer_sales = $db->query($sql, ':domain_id', $auth_session->domain_id);

  $total_sales = 0;
  $customers = array();

  while($customer = $customer_sales->fetch()) {
    $total_sales += $customer['sum_total'];
    array_push($customers, $customer);
  }

  $smarty -> assign('data', $customers);
  $smarty -> assign('total_sales', $total_sales);

  $smarty -> assign('pageActive', 'report');
  $smarty -> assign('active_tab', '#home');
?>
