<?php

   if ($db_server == 'pgsql') {
      $sql = "SELECT
        iv.id,
        b.name AS biller,
        c.name AS customer,

        coalesce(ii.total, 0) AS inv_total,
        coalesce(ap.total, 0) AS inv_paid,
        coalesce(ii.total, 0) - coalesce(ap.total, 0) as inv_owing,

        to_char(iv.date,'YYYY-MM-DD') as date,
        age(iv.date) as age,
        (CASE   WHEN age(iv.date) <= '14 days'::interval THEN '0-14'
                WHEN age(iv.date) <= '30 days'::interval THEN '15-30'
                WHEN age(iv.date) <= '60 days'::interval THEN '31-60'
                WHEN age(iv.date) <= '90 days'::interval THEN '61-90'
                ELSE '90+'
        END) as aging

	FROM
        ".TB_PREFIX."invoices iv INNER JOIN
	".TB_PREFIX."biller b ON (b.id = iv.biller_id) INNER JOIN
	".TB_PREFIX."customers c ON (c.id = iv.customer_id) LEFT JOIN
        (SELECT i.invoice_id, sum(i.total) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
        (SELECT p.ac_inv_id, sum(p.ac_amount) AS total
         FROM ".TB_PREFIX."payments p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
	ORDER BY
        age DESC;
    ";
       } else {
	
          $sql = "SELECT
			iv.id, 
			b.name AS biller, 
			c.name AS customer, 
--			COUNT(ii.invoice_id) AS items,
			SUM(COALESCE(ii.total, 0)) AS inv_total,
			COALESCE(ap.inv_paid, 0) AS inv_paid,
--    inv_total - inv_paid AS inv_owing,
			SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing,

			DATE_FORMAT(`date`,'%Y-%m-%e') AS `date`,
			(SELECT DATEDIFF(NOW(),`date`)) AS age,
			(CASE WHEN DATEDIFF(NOW(),`date`) <= 14 THEN '0-14'
				  WHEN DATEDIFF(NOW(),`date`) <= 30 THEN '15-30'
				  WHEN DATEDIFF(NOW(),`date`) <= 60 THEN '31-60'
				  WHEN DATEDIFF(NOW(),`date`) <= 90 THEN '61-90'
				  ELSE '90+'
			END ) AS Aging

		FROM
            ".TB_PREFIX."invoices iv  
            LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id    = iv.id      AND ii.domain_id = iv.domain_id)  
            LEFT JOIN ".TB_PREFIX."biller b         ON (iv.biller_id     = b.id       AND  b.domain_id = iv.domain_id)
            LEFT JOIN ".TB_PREFIX."customers c      ON (iv.customer_id   = c.id       AND  c.domain_id = iv.domain_id)
            LEFT JOIN ".TB_PREFIX."preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            LEFT JOIN (
				SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid 
					FROM ".TB_PREFIX."payment 
					GROUP BY ac_inv_id, domain_id
			) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
		WHERE   
				pr.status    = 1
			AND iv.domain_id = :domain_id
		GROUP BY 
			iv.id
		HAVING 
			inv_owing > 0
		ORDER BY 
			age DESC;
		";
    }

    $invoice_results = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

    $total_owed = 0;
    $periods = array();

    while($invoice = $invoice_results->fetch()) {
      $periods[$invoice['Aging']]['name'] = $invoice['Aging'];

      if (!array_key_exists('invoices', $periods[$invoice['Aging']])) {
         $periods[$invoice['Aging']]['invoices'] = array();
      }

      array_push($periods[$invoice['Aging']]['invoices'], $invoice);

      $periods[$invoice['Aging']]['sum_total'] += $invoice['inv_owing'];

      $total_owed += $invoice['inv_owing'];
    }

    $smarty -> assign('data', $periods);
    $smarty -> assign('total_owed', $total_owed);

    $smarty -> assign('pageActive', 'report');
    $smarty -> assign('active_tab', '#home');
?>
