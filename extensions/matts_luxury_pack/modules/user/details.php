<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/user/details.php
 * 	add a user details page
 *
 * Authors:
 *	yumatechnical@gmail.com
 *
 * Last edited:
 * 	2016-08-29
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
global $smarty, $LANG;
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$id = $_GET['id'];

$user = user::getUser($id);
$roles = user::getUserRoles();

$smarty->assign('enabled_options', array(0 => $LANG['disabled'], 1 => $LANG['enabled']));

$smarty->assign('user', $user);
$smarty->assign('roles', $roles);
$smarty->assign('customers', getActiveCustomers());//Matt
$smarty->assign('billers', getActiveBillers());//Matt

$smarty -> assign('pageActive', 'user');

$subPageActive = $_GET['action'] =="view"  ? "user_view" : "user_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#people');
