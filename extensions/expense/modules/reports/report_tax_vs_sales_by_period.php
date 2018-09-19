<?php
/*
 * Script: report_sales_by_period.php
 *     Sales reports by period add page
 *
 * Authors:
 *     Justin Kelly
 *
 * Last edited:
 *      2008-05-13
 *
 * License:
 *     GPL v3
 *
 * Website:
 *     https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty;

checkLogin();

$domain_id = domain_id::get();

/*
 * Get earliest invoice date
 */
$sql = "SELECT MIN(date) AS date FROM " . TB_PREFIX . "invoices WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', $domain_id);
$invoice_start_array = $sth->fetch();

// $invoice_start = dbQuery($sql);
// $invoice_start_array= mysql_fetch_array($invoice_start);
$first_invoice_year = date('Y', strtotime($invoice_start_array['date']));
/*
 * Get total for each month of each year from first invoice
 */
$this_year = date('Y');
$year = $first_invoice_year;
// $years[]=$first_invoice_year ;
/*
 * loop for each year
 */
$total_sales    = array();
$tax_summary    = array();
$total_payments = array();
$years          = array();
while($year <= $this_year) {
    // loop for each month
    $month = 01;
    while($month <= 12) {
        // make month nice for mysql - accounts table doesnt like it if not 08 etc..
        if ($month < 10) {
            $month = "0" . $month;
        }

        $total_month_sales_sql = "SELECT SUM(iit.tax_amount) AS month_total 
            FROM " . TB_PREFIX . "invoice_item_tax iit 
                LEFT JOIN " . TB_PREFIX . "invoice_items ii ON (iit.invoice_item_id = ii.id)
                LEFT JOIN " . TB_PREFIX . "invoices i ON (i.id = ii.invoice_id AND i.domain_id = ii.domain_id)
                LEFT JOIN " . TB_PREFIX . "preferences p ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
            WHERE  
                ii.domain_id = :domain_id
            AND p.status = '1' 
            AND i.date LIKE '$year-$month%'";

        $total_month_sales = dbQuery($total_month_sales_sql, ':domain_id', $domain_id);
        $total_month_sales_array = $total_month_sales->fetch();
        
        $total_sales[$year][$month] = $total_month_sales_array['month_total'];
        if ($total_sales[$year][$month] == "") {
            $total_sales[$year][$month] = "-";
        }
        
        /*
         * Payment
         */
        $total_month_payments_sql = "SELECT SUM(et.tax_amount) AS month_total_payments 
            FROM " . TB_PREFIX . "expense_item_tax et 
                 LEFT JOIN " . TB_PREFIX . "expense e ON (e.id = et.expense_id)
            WHERE
                e.domain_id = :domain_id
            AND e.date LIKE '$year-$month%'";
        
        $total_month_payments = dbQuery($total_month_payments_sql, ':domain_id', $domain_id);
        $total_month_payments_array = $total_month_payments->fetch();
        
        $total_payments[$year][$month] = $total_month_payments_array['month_total_payments'];
        if ($total_payments[$year][$month] == "") {
            $total_payments[$year][$month] = "-";
        }
        
        $tax_summary[$year][$month] = $total_month_sales_array['month_total'] - $total_month_payments_array['month_total_payments'];
        
        $tax_summary[$year][$month] == "0" ? $tax_summary[$year][$month] = "-" : $tax_summary[$year][$month] = $tax_summary[$year][$month];
        
        $month++;
    }
    /*
     * Sales
     */
    $total_year_sales_sql = "SELECT SUM(iit.tax_amount) AS year_total 
        FROM " . TB_PREFIX . "invoice_item_tax iit 
            LEFT JOIN " . TB_PREFIX . "invoice_items ii ON (iit.invoice_item_id = ii.id)
            LEFT JOIN " . TB_PREFIX . "invoices i ON (i.id = ii.invoice_id AND i.domain_id = ii.domain_id)
            LEFT JOIN " . TB_PREFIX . "preferences p ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
        WHERE  
            ii.domain_id = :domain_id
        AND p.status = '1' 
        AND i.date LIKE '$year%'";
    
    $total_year_sales = dbQuery($total_year_sales_sql, ':domain_id', $domain_id);
    $total_year_sales_array = $total_year_sales->fetch();
    
    $total_sales[$year]['Total'] = $total_year_sales_array['year_total'];
    if ($total_sales[$year]['Total'] == "") {
        $total_sales[$year]['Total'] = "-";
    }
    
    /*
     * Payment
     */
    $total_year_payments_sql = "SELECT SUM(et.tax_amount) AS year_total_payments 
        FROM " . TB_PREFIX . "expense_item_tax et 
             LEFT JOIN " . TB_PREFIX . "expense e ON (e.id = et.expense_id)
        WHERE
            e.domain_id = :domain_id
        AND e.date LIKE '$year%'";
    
    $total_year_payments = dbQuery($total_year_payments_sql, ':domain_id', $domain_id);
    $total_year_payments_array = $total_year_payments->fetch();
    
    $total_payments[$year]['Total'] = $total_year_payments_array['year_total_payments'];
    if ($total_payments[$year]['Total'] == "") {
        $total_payments[$year]['Total'] = "-";
    }
    
    $tax_summary[$year]['Total'] = $total_year_sales_array['year_total'] - $total_year_payments_array['year_total_payments'];
    $tax_summary[$year]['Total'] == "0" ? $tax_summary[$year]['Total'] = "-" : $tax_summary[$year]['Total'] = $tax_summary[$year]['Total'];
    $years[] = $year;
    $year++;
}

$smarty->assign('total_sales', $total_sales);
$smarty->assign('total_payments', $total_payments);
$smarty->assign('tax_summary', $tax_summary);
// $years = array(2006,2007,2008);
$years = array_reverse($years);
$smarty->assign('years', $years);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
