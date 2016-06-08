<?php
/*
 * Script: details.php
 *      User details page
 * Authors:
 *      Justin Kelly, Nicolas Ruflin, Rich Rowley
 *
 * Last edited:
 *      2016-06-06
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      http://www.simpleinvoices.org
 */

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// get the invoice id
$id = $_GET['id'];

$user = user::getUser($id);
$roles = user::getUserRoles();

$smarty->assign("pwd_pattern", UserSecurity::buildPwdPattern());

$smarty->assign('user', $user);
$smarty->assign('roles', $roles);

$smarty->assign('pageActive', 'user');
$subPageActive = $_GET['action'] == "view" ? "user_view" : "user_edit";
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#people');
