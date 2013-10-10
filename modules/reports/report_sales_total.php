<?php 
    $sql = "SELECT SUM(ii.total) AS sum_total
    FROM 
        ".TB_PREFIX."invoice_items ii
		INNER JOIN ".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id) 
        INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id) 
    WHERE
           pr.status = '1'
       AND ii.domain_id = :domain_id
    ";

    $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

    $smarty->assign('total_sales', $sth->fetchColumn());
    $smarty -> assign('pageActive', 'report_sale');
    $smarty -> assign('active_tab', '#money');
?>
