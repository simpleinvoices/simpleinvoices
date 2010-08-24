<?php
/*
* Script: details.php
* 	Custom fields details page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#table

#get the invoice id
$cf_id = $_GET["id"];

global $dbh;
#customer query
$print_product = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE cf_id = :id";
$sth = dbQuery($print_product, ':id', $cf_id) or die(end($dbh->errorInfo()));

$cf = $sth->fetch();
$cf['name'] = get_custom_field_name($cf['cf_custom_field']);


$pageActive = "options";

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign("cf",$cf);

$smarty -> assign('pageActive', 'custom_field');
$subPageActive = $_GET['action'] =="view"  ? "custom_fields_view" : "custom_fields_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#setting');
?>
