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
$print_product = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE cf_id = :id AND domain_id = :domain_id";
$sth = dbQuery($print_product, ':id', $cf_id, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));

$cf = $sth->fetch();
$cf['name'] = get_custom_field_name($cf['cf_custom_field']);


$pageActive = "options";

$bladeView -> assign('pageActive', $pageActive);
$bladeView -> assign("cf",$cf);

$bladeView -> assign('pageActive', 'custom_field');
$subPageActive = $_GET['action'] =="view"  ? "custom_fields_view" : "custom_fields_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#setting');
?>
