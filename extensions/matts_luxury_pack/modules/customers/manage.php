<?php
/*
* Script: manage.php
* 	Customers manage page
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
global $cc_months, $pagerows;

/**/
$smarty -> assign('number_of_customers', ncustomers());
$defaults = getSystemDefaults();
$smarty->assign ("defaults", getSystemDefaults());
$smarty->assign ("array", $pagerows);
/**/
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('active_tab', '#people');
$smarty->assign('cc_months', $cc_months);
