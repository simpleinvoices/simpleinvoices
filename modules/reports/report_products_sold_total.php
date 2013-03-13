<?php 

  $sql = "SELECT p.description, sum(ii.quantity) AS sum_quantity
      FROM ".TB_PREFIX."invoice_items ii INNER JOIN
      ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id) INNER JOIN
      ".TB_PREFIX."products p ON (p.id = ii.product_id)
      WHERE p.visible GROUP BY p.description";

  $product_sales = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));

  $total_quantity = 0;
  $products = array();

  while($product = $product_sales->fetch()) {
    $total_quantity += $product['sum_quantity'];
    array_push($products, $product);
  }

  $smarty -> assign('data', $products);
  $smarty -> assign('total_quantity', $total_quantity);

	$smarty -> assign('pageActive', 'report');
	$smarty -> assign('active_tab', '#home');
?>