<?php
/*
 * Script: details.php
 *     Custom fields details page
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $auth_session, $dbh, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//get the invoice id
$cf_id = $_GET["id"];

global $dbh;

$print_product = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE cf_id = :id AND domain_id = :domain_id";
$sth = dbQuery($print_product, ':id', $cf_id, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));

$cf = $sth->fetch();
$cf['name'] = get_custom_field_name($cf['cf_custom_field']);


$pageActive = "options";

$smarty->assign('pageActive', $pageActive);
$smarty->assign("cf",$cf);

$smarty->assign('pageActive', 'custom_field');
$subPageActive = $_GET['action'] =="view"  ? "custom_fields_view" : "custom_fields_edit" ;
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#setting');
