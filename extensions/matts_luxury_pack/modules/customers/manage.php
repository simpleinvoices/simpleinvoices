<?php
/*
* Script: ./extensions/matts_luxury_pack/modules/customers/manage.php
* 	Customers manage page
*
* Authors:
*	 yumatechnical@gmail.com
*
* Last edited:
* 	 2016-08-30
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
global $smarty, $LANG, $pdoDb, $config;
global $cc_months, $cc_years, $pagerows;//Matt

// @formatter:off
/*$pdoDb->addSimpleWhere("domain_id", domain_id::get());
$pdoDb->addToFunctions("COUNT(*) AS count");
$rows  = $pdoDb->request("SELECT", "customers");
$row   = $rows[0];
$count = $row["count"];
$smarty->assign('number_of_customers', $count);*/
$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
/**/
$smarty -> assign('number_of_customers', ncustomers());
$smarty->assign ("defaults", getSystemDefaults());
$smarty->assign ("array", $pagerows);
$smarty->assign('cc_months', $cc_months);
/**/
// @formatter:on
