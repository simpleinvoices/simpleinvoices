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

	$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', domain_id::get());
	$number_of_customers  = $sth->fetch(PDO::FETCH_ASSOC);

$bladeView -> assign('number_of_customers', $number_of_customers);

$bladeView -> assign('pageActive', 'customer');
$bladeView -> assign('active_tab', '#people');
?>
