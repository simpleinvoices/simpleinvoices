<?php
/*
* Script: report_sales_by_period.php
* 	Sales reports by period add page
*
* Authors:
*	 Justin Kelly
*	Francois Dechery, aka Soif
*
* Last edited:
* 	 2008-05-13
*
* License:
*	 GPL v3
*
* Website:
* 	https://simpleinvoices.group/doku.php?id=si_wiki:menu*/

checkLogin();

$max_years = 10;
$domain_id = domain_id::get();

// Get earliest invoice date
$pdoDb->addToFunctions(new FunctionStmt("MIN", new DbField("iv.date"), "date"));

$jn = new Join("INNER", "preferences", "pr");
$jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
$jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
$pdoDb->addToJoins($jn);

$pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
$pdoDb->addSimpleWhere("iv.domain_id", $domain_id);

$rows = $pdoDb->request("SELECT", "invoices", "iv");
$first_invoice_year = date('Y', strtotime($rows[0]['date']));

// Get total for each month of each year from first invoice
$this_year = date('Y');
$year = $first_invoice_year;

$total_years = $this_year - $first_invoice_year + 1;
if ($total_years > $max_years) {
    $year = $this_year - $max_years + 1;
}

function _myRate($this_year_amount, $last_year_amount, $precision = 2) {
    if (!$last_year_amount) {return '';}
    $rate = round(($this_year_amount - $last_year_amount) / $last_year_amount * 100, $precision);
    return $rate;
}

// loop for each year

$years = array();
$data = array();

while($year <= $this_year) {
    // loop for each month
    $month = 1;
    while($month <= 12) {
        // make month nice for mysql - accounts table doesnt like it if not 08 etc..
        if ($month < 10) {
            $month = "0" . $month;
        }

        // Monthly Sales ----------------------------
        $pdoDb->addToFunctions(new FunctionStmt("SUM", new DbField("ii.total"), "month_total"));

        $jn = new Join("INNER", "invoices", "iv");
        $jn->addSimpleItem("iv.id", new DbField("ii.invoice_id"), "AND");
        $jn->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("INNER", "preferences", "pr");
        $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
        $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);
        
        $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
        $pdoDb->addSimpleWhere("pr.domain_id", new DbField("iv.domain_id"), "AND");
        $pdoDb->addToWhere(new WhereItem(false, "iv.date", "LIKE", "$year-$month%", false));

        $rows = $pdoDb->request("SELECT", "invoice_items", "ii");

        $data['sales']['months'     ][$month][$year] = $rows[0]['month_total'];
        $data['sales']['months_rate'][$month][$year] = _myRate($data['sales']['months'][$month][$year], $data['sales']['months'][$month][$year - 1]);

        // Monthly Payment ----------------------------
        $pdoDb->addToFunctions(new FunctionStmt("SUM", new DbField("ac_amount"), "month_total_payments"));
        $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
        $pdoDb->addToWhere(new WhereItem(false, "ac_date", "LIKE", "$year-$month%", false));

        $rows = $pdoDb->request("SELECT", "payment");

        $data['payments']['months'     ][$month][$year] = $rows[0]['month_total_payments'];
        $data['payments']['months_rate'][$month][$year] = _myRate($data['payments']['months'][$month][$year], $data['payments']['months'][$month][$year - 1]);

        $month++;
    }

    // Total Annual Sales ----------------------------
    $pdoDb->addToFunctions(new FunctionStmt("SUM", new DbField("ii.total"), "year_total"));

    $jn = new Join("INNER", "invoices", "iv");
    $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
    $jn->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
    $pdoDb->addToJoins($jn);

    $jn = new Join("INNER", "preferences", "pr");
    $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
    $jn->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
    $pdoDb->addToJoins($jn);

    $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
    $pdoDb->addSimpleWhere("ii.domain_id", $domain_id, "AND");
    $pdoDb->addToWhere(new WhereItem(false, "iv.date", "LIKE", "$year%", false));
    $rows = $pdoDb->request("SELECT", "invoice_items", "ii");

    $data['sales']['total'     ][$year] = $rows[0]['year_total'];
    $data['sales']['total_rate'][$year] = _myRate($data['sales']['total'][$year], $data['sales']['total'][$year - 1]);

    // Total Annual Payment ----------------------------
    $pdoDb->addToFunctions(new FunctionStmt("SUM", "ac_amount", "year_total_payments"));
    $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
    $pdoDb->addToWhere(new WhereItem(false, "ac_date", "LIKE", "$year%", false));
    $rows = $pdoDb->request("SELECT", "payment");

    $data['payments']['total'     ][$year] = $rows[0]['year_total_payments'];
    $data['payments']['total_rate'][$year] = _myRate($data['payments']['total'][$year], $data['payments']['total'][$year - 1]);

    $years[] = $year;
    $year++;
}

$years = array_reverse($years);
$smarty->assign('data'     , $data);
$smarty->assign('all_years', $years);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
