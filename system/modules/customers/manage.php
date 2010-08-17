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

	$sql = "SELECT count(*) as count FROM ".TB_PREFIX."customers";
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	$number_of_customers  = $sth->fetch(PDO::FETCH_ASSOC);

$smarty -> assign('number_of_customers', $number_of_customers);
$smarty -> assign("customers",$customers);

$smarty -> assign('pageActive', 'customer');
$smarty -> assign('active_tab', '#people');
?>
