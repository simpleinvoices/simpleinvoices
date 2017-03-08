<?php
global $smarty;
$sql = "SELECT pr.index_group AS `group`,
               GROUP_CONCAT(DISTINCT pr.pref_description SEPARATOR ',') AS template,
               COUNT(DISTINCT ii.invoice_id) AS `count`,
               SUM(ii.total) AS sum_total
        FROM " . TB_PREFIX . "invoice_items ii
        INNER JOIN " . TB_PREFIX . "invoices iv    ON (iv.id      = ii.invoice_id    AND iv.domain_id = ii.domain_id) 
        INNER JOIN " . TB_PREFIX . "preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id) 
        WHERE pr.status = '1'
          AND ii.domain_id = :domain_id
        GROUP BY pr.index_group";
$sth = dbQuery($sql, ':domain_id', domain_id::get());

$grand_total_sales = 0;
$total_sales = array();
while($sales = $sth->fetch()) {
    $grand_total_sales += $sales['sum_total'];
    array_push($total_sales, $sales);
}

$smarty->assign('data', $total_sales);
$smarty->assign('grand_total_sales', $grand_total_sales);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
