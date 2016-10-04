<?php
/*
* Script: manage.php
* 	Custom fields manage page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

global $dbh;

$sql = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE domain_id = :domain_id ORDER BY cf_custom_field";
$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));

$cfs = null;

$number_of_rows = 0;
for($i=0; $cf = $sth->fetch();$i++) {
	$cfs[$i] = $cf;
	$cfs[$i]['filed_name'] = get_custom_field_name($cf['cf_custom_field']);
	$number_of_rows = $i;
}

$smarty -> assign("cfs",$cfs);

$smarty -> assign('pageActive', 'custom_field');
$smarty -> assign('active_tab', '#setting');
?>