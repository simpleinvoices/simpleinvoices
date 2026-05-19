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
* 	http://www.simpleinvoices.org
 */

checkLogin();

//get user roles
$roles = user::getUserRoles();

if ($_POST['email'] != "") {
	include ("./modules/user/save.php");
}

$bladeView->assign('save', $save);
$bladeView->assign('roles', $roles);
$bladeView->assign('userUiLanguageList', si_get_ui_language_list_sorted());
$bladeView->assign('userPreferredValue', trim((string) ($_POST['preferred_language'] ?? '')));
$bladeView->assign('userSaveCsrfToken', siNonce('user_save'));

$bladeView -> assign('pageActive', 'user');
$bladeView -> assign('subPageActive', 'user_add');
$bladeView -> assign('active_tab', '#people');

?>
