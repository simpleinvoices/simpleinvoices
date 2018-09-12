<?php
/*
 * Script: add.php
 * 	Billers add page
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
 * 	https://simpleinvoices.group
 */
global $smarty;

checkLogin();

$roles = user::getUserRoles();

$saved = false;
if (!empty($_POST['email'])) {
	include ("modules/user/save.php");
}

$smarty->assign('save', $saved);
$smarty->assign('roles', $roles);

$smarty->assign('pageActive', 'user');
$smarty->assign('subPageActive', 'user_add');
$smarty->assign('active_tab', '#people');
