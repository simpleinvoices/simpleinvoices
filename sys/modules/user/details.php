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


$smarty->assign('user', $user);
$smarty->assign('roles', $roles);
/*
$smarty -> assign('enabled', array(
                                0 => $LANG[disabled],
				1 => $LANG[enabled]
			)
		);
 */
 

$smarty -> assign('pageActive', 'user');
$subPageActive = $_GET['action'] =="view"  ? "user_view" : "user_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#people');
?>
