<?php

/*
* Script: details.php
* 	Biller details page
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

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$id = $_GET['id'];

$user = user::getUser($id);
$roles = user::getUserRoles();

$saveReturnModule = '';
$saveReturnView   = '';
if (!empty($_GET['return_module']) && !empty($_GET['return_view'])) {
    $rm = filenameEscape((string) $_GET['return_module']);
    $rv = filenameEscape((string) $_GET['return_view']);
    if (($rm === 'admin' && $rv === 'domain_admin_users')
        || ($rm === 'domain_admin' && $rv === 'all_users')) {
        $saveReturnModule = $rm;
        $saveReturnView   = $rv;
    }
}

$bladeView->assign('user', $user);
$bladeView->assign('roles', $roles);
$bladeView->assign('userSaveCsrfToken', siNonce('user_save'));
$bladeView->assign('saveReturnModule', $saveReturnModule);
$bladeView->assign('saveReturnView', $saveReturnView);
/*
$bladeView -> assign('enabled', array(
                                0 => $LANG['disabled'],
				1 => $LANG['enabled']
			)
		);
 */
 

$bladeView -> assign('pageActive', 'user');
$subPageActive = $_GET['action'] =="view"  ? "user_view" : "user_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#people');
?>
