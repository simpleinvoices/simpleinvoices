<?php 
    $sql = "select sum(ii.total) as total 
            from 
                ".TB_PREFIX."invoice_items ii,
                ".TB_PREFIX."invoices i,
                ".TB_PREFIX."preferences p
            where
                i.preference_id = p.pref_id
                and 
                i.id = ii.invoice_id
                and
                p.status = '1';
                ";

    $sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));

    $smarty->assign('total_sales', $sth->fetchColumn());
    $smarty -> assign('pageActive', 'report_sale');
    $smarty -> assign('active_tab', '#money');
?>
