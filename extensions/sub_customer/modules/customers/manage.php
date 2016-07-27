<?php
/*
 *  Script: manage.php
 *      Customers manage page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$path1 = './extensions/sub_customers/include';
$curr_path = get_include_path();
if (!strstr($curr_path, $path1)) {
    $path2 = $path1 . '/class';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path1 . PATH_SEPARATOR . $path2);
}

// Create & initialize DB table if it doesn't exist.
SubCustomers::addParentCustomerId();

$pdoDb->addSimpleWhere("domain_id", domain_id::get());
$pdoDb->addToFunctions("count(*) AS count");
$rows = $pdoDb->request("SELECT", "customers");
$row = $rows[0];
$count = $row["count"];
$smarty->assign('number_of_customers', $count);

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
