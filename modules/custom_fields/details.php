<?php
/*
* Script: details.php
* 	Custom fields details page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#table

#get the invoice id
$cf_id = $_GET["submit"];

global $dbh;
#customer query
$print_product = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE cf_id = :id";
$sth = dbQuery($print_product, ':id', $cf_id) or die(end($dbh->errorInfo()));

$cf = $sth->fetch();
$cf['name'] = get_custom_field_name($cf['cf_custom_field']);


$pageActive = "options";

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign("cf",$cf);
?>
