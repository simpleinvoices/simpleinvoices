<?php
/*
 * Script: details.php
 *      User details page
 * Authors:
 *      Justin Kelly, Nicolas Ruflin,
 *      Rich Rowley
 *
 * Last edited:
 *      2016-07-21
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 */

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
global $smarty, $LANG, $pdoDb, $auth_session;

// get the invoice id
$id = $_GET['id'];

$user = user::getUser($id);
$roles = user::getUserRoles();

$domain_id = $auth_session->domain_id;

$pdoDb->setSelectList(array("id","name"));
$pdoDb->addSimpleWhere("domain_id", $domain_id);
$cust_info = $pdoDb->request("SELECT", "customers");

$pdoDb->setSelectList(array("id","name"));
$pdoDb->addSimpleWhere("domain_id", $domain_id);
$billers = $pdoDb->request("SELECT", "biller");

if ($user['user_id'] == 0) {
    $user_id_desc = '0 - User';
} else if ($user['role_name'] == 'customer') {
    $user_id_desc = $user['user_id'] . " - Undefined";
    foreach($cust_info as $cust) {
        if ($cust['id'] == $user['user_id']) {
            $user_id_desc = $user['user_id'] . " - " . $cust['name'];
            break;
        }
    }
} else {
    $user_id_desc = $user['user_id'] . " - Undefined";
    foreach($billers as $biller) {
        if ($biller['id'] == $user['user_id']) {
            $user_id_desc = $user['user_id'] . " - " . $biller['name'];
            break;
        }
    }
}

// Serialize the arrays so they can be put on screen as hidden items.
$l = array();
foreach($cust_info as $cust) {
    $l[] = $cust['id'] . " - " . $cust['name'];
}
$cust = implode('~', $l);

$l = array();
foreach($billers as $biller) {
    $l[] = $biller['id'] . " - " . $biller['name'];
}
$bilr = implode('~', $l);

$smarty->assign('enabled_options', array(0 => $LANG['disabled'], 1 => $LANG['enabled']));

$smarty->assign('user_id_desc', $user_id_desc);
$smarty->assign('orig_role_name', $user['role_name']);
$smarty->assign('orig_user_id', $user['user_id']);

$smarty->assign("pwd_pattern", UserSecurity::buildPwdPattern());

$smarty->assign('user', $user);
$smarty->assign('roles', $roles);
$smarty->assign('cust_info', $cust_info);
$smarty->assign('billers', $billers);
$smarty->assign('cust', $cust);
$smarty->assign('bilr', $bilr);

$smarty->assign('pageActive', 'user');
$subPageActive = $_GET['action'] == "view" ? "user_view" : "user_edit";
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#people');
