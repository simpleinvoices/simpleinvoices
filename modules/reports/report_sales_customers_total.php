<?php 
//   include phpreports library
require_once("./include/reportlib.php");

   $sSQL = "SELECT c.name, sum(ii.total) as sum_total
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
      GROUP BY c.name 

";

   $oRpt->setXML("./modules/reports/report_sales_customers_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
