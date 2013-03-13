<?php
  $sql = "SELECT 
                b.name, 
                sum(ii.total) AS sum_total
            FROM 
                ".TB_PREFIX."biller b 
            INNER JOIN
              ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id)
            INNER JOIN
              ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id)
            INNER JOIN
              ".TB_PREFIX."preferences p ON (p.pref_id = iv.preference_id)
            WHERE
                p.status ='1'
            GROUP BY 
                b.name";

  $biller_sales = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));

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