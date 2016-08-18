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

/**/
$smarty -> assign('number_of_customers', ncustomers());
$defaults = getSystemDefaults();
$smarty->assign ("defaults", getSystemDefaults());
$smarty->assign ("array", array(5, 10, 15, 20, 25, 30, 35, 50, 100, 500));
/**/
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('active_tab', '#people');
?>
