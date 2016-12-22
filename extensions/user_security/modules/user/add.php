<?php
/*
 * Script: add.php
 *      User add page
 *
 * Authors:
 *      Justin Kelly, Nicolas Ruflin,
 *      Rich Rowley
 *
 * Last edited:
 *      2016-06-24
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     http://www.simpleinvoices.org
 */
global $smarty;

checkLogin();

//get user roles
$roles = user::getUserRoles();

if (!empty($_POST['username'])) {
    include ("./extensions/user_security/modules/user/save.php");
}

$smarty->assign("pwd_pattern", UserSecurity::buildPwdPattern());

$smarty->assign('roles', $roles);

$smarty->assign('pageActive', 'user');
$smarty->assign('subPageActive', 'user_add');
$smarty->assign('active_tab', '#people');
