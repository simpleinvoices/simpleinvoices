<?php 

  $sql = "SELECT c.name, sum(ii.total) as sum_total
          FROM 
                ".TB_PREFIX."customers c, 
                ".TB_PREFIX."invoices i,
                ".TB_PREFIX."invoice_items ii, 
                ".TB_PREFIX."preferences p
          where
            i.customer_id = c.id
            AND
            ii.invoice_id = i.id
            AND 
            i.preference_id = p.pref_id
            AND
                p.status = '1'
          GROUP BY c.name";

  $customer_sales = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));

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
