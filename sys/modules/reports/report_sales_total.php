<?php 
//   include phpreports library
require_once("./include/reportlib.php");

   $sSQL = "select sum(ii.total) as total 
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

   $oRpt->setXML("./modules/reports/report_sales_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report_sale');
$smarty -> assign('active_tab', '#money');
?>
