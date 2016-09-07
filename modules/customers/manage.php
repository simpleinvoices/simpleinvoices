<?php
/*
 *  Script: manage.php
 *      Customers manage page
 *
 *  Last modified:
 *      2016-07-27
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
global $smarty, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$pdoDb->addSimpleWhere("domain_id", domain_id::get());
$pdoDb->addToFunctions("COUNT(*) AS count");
$rows  = $pdoDb->request("SELECT", "customers");
$row   = $rows[0];
$count = $row["count"];
$smarty->assign('number_of_customers', $count);
$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
// @formatter:on
