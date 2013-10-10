<?php

   if ($db_server == 'pgsql') {
      $sql = "SELECT
        iv.id,
        iv.index_id AS index_id,
        b.name AS biller,
        c.name AS customer,

        coalesce(ii.total, 0) AS inv_total,
        coalesce(ap.total, 0) AS inv_paid,
        coalesce(ii.total, 0) - coalesce(ap.total, 0) AS inv_owing,
        iv.date
FROM
        ".TB_PREFIX."invoices iv INNER JOIN
	".TB_PREFIX."customers c ON (c.id = iv.customer_id) INNER JOIN
	".TB_PREFIX."biller b ON (b.id = iv.biller_id) LEFT JOIN
        (SELECT i.invoice_id, sum(i.total) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
        (SELECT p.ac_inv_id, sum(p.ac_amount) AS total
         FROM ".TB_PREFIX."payment p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
ORDER BY
        inv_owing DESC;
";
   } else {
      $sql = "SELECT
      iv.id, 
      iv.index_id,
      b.name AS biller, 
      c.name AS customer, 
      SUM(COALESCE(ii.total, 0)) AS inv_total,
      COALESCE(ap.inv_paid, 0) AS inv_paid,
      SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing,
      `date`
	FROM
        ".TB_PREFIX."invoices iv  
        LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id    = iv.id      AND ii.domain_id = iv.domain_id)  
        LEFT JOIN ".TB_PREFIX."biller b         ON (iv.biller_id     =  b.id      AND  b.domain_id = iv.domain_id)
        LEFT JOIN ".TB_PREFIX."customers c      ON (iv.customer_id   =  c.id      AND  c.domain_id = iv.domain_id)
        LEFT JOIN (
	    SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid 
			FROM ".TB_PREFIX."payment 
			GROUP BY ac_inv_id, domain_id
	) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
	WHERE
		iv.domain_id = :domain_id
	GROUP BY
		iv.id
	ORDER BY
        inv_owing DESC;
";
   }

  $invoice_results = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

  $total_owed = 0;
  $invoices = array();

  while($invoice = $invoice_results->fetch()) {
    $total_owed += $invoice['inv_owing'];
    array_push($invoices, $invoice);
  }

  $smarty -> assign('data', $invoices);
  $smarty -> assign('total_owed', $total_owed);   

  $smarty -> assign('pageActive', 'report');
  $smarty -> assign('active_tab', '#home');
?>