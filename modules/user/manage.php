<?php
/*
* Script: manage.php
* 	Manage users page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

$sql = "SELECT count(*) as count FROM ".TB_PREFIX."user";
$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows  = $sth->fetch(PDO::FETCH_ASSOC);

$bladeView -> assign("number_of_rows",$number_of_rows);

$bladeView -> assign('pageActive', 'user');
$bladeView -> assign('active_tab', '#people');
?>