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
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$number_of_customers  = $sth->fetch(PDO::FETCH_ASSOC);

$pageActive = "customers";

$smarty -> assign('number_of_customers', $number_of_customers);
$smarty -> assign('pageActive', $pageActive);
$smarty -> assign("customers",$customers);

?>
