<?php

  if ($db_server == 'pgsql') {
      $sql = "SELECT
        c.id AS cid,
        c.name AS customer,
        sum(coalesce(ii.total, 0)) AS inv_total,
        sum(coalesce(ap.ac_amount, 0)) AS inv_paid,
        sum(coalesce(ii.total, 0)) -
        sum(coalesce(ap.ac_amount, 0)) AS inv_owing

FROM
        ".TB_PREFIX."customers c LEFT JOIN
        ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) LEFT JOIN
	(SELECT i.invoice_id, coalesce(sum(i.total), 0) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
	(SELECT p.ac_inv_id, coalesce(sum(p.ac_amount), 0) AS ac_amount
         FROM ".TB_PREFIX."payment p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
GROUP BY
        c.id, c.name
ORDER BY
        inv_owing DESC;
   ";
  } else {
      $sql = "
SELECT
        c.id AS cid
      , c.name AS customer
      , SUM(COALESCE(ii.total, 0)) AS inv_total
      , COALESCE(ap.inv_paid, 0) AS inv_paid
      , SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing
FROM
      ".TB_PREFIX."customers c 
      LEFT JOIN ".TB_PREFIX."invoices iv      ON (iv.customer_id = c.id AND iv.domain_id = c.domain_id)
      LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
      LEFT JOIN ".TB_PREFIX."preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
      LEFT JOIN (
  SELECT 
	  iv1.customer_id
	, ap1.domain_id
	, SUM(COALESCE(ap1.ac_amount, 0)) AS inv_paid 
  FROM  ".TB_PREFIX."payment ap1
      LEFT JOIN ".TB_PREFIX."invoices iv1   ON (ap1.ac_inv_id = iv1.id AND ap1.domain_id = iv1.domain_id)
      LEFT JOIN ".TB_PREFIX."preferences pr1 ON (pr1.pref_id = iv1.preference_id AND pr1.domain_id = iv1.domain_id)
  WHERE
      pr1.status = 1
  GROUP BY iv1.customer_id, ap1.domain_id
      ) ap ON (ap.customer_id = c.id AND ap.domain_id = c.domain_id)
WHERE
          pr.status   = 1
      AND c.domain_id = :domain_id
GROUP BY 
	c.id;
";
  }

  $customer_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);

  $total_owed = 0;
  $customers = array();

  while($customer = $customer_results->fetch()) {
    $total_owed += $customer['inv_owing'];
    array_push($customers, $customer);
  }

  $smarty -> assign('data', $customers);
  $smarty -> assign('total_owed', $total_owed);   

  $smarty -> assign('pageActive', 'report');
  $smarty -> assign('active_tab', '#home');
?>
