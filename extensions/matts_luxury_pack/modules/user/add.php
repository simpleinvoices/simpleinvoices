<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/user/add.php
 * 	add a user page
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
global $smarty;

checkLogin();

$roles = user::getUserRoles();

$saved = false;
if (!empty($_POST['email'])) {
	include ("./modules/user/save.php");
}

$smarty->assign('save', $saved);
$smarty->assign('roles', $roles);
$smarty->assign('customers', getActiveCustomers());//Matt
$smarty->assign('billers', getActiveBillers());//Matt

$smarty->assign('pageActive', 'user');
$smarty->assign('subPageActive', 'user_add');
$smarty->assign('active_tab', '#people');
