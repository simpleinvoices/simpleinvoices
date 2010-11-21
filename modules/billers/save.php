<?php
/*
* Script: save.php
* 	Biller save page
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

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert biller

$saved = false;

if ( $op === 'insert_biller') {
	
	if($id = insertBiller()) {
 		$saved = true;
 		//saveCustomFieldValues($_POST['categorie'],lastInsertId());
 	}
}

if ($op === 'edit_biller' ) {
	if (isset($_POST['save_biller']) && updateBiller()) {
		$saved = true;
		//updateCustomFieldValues($_POST['categorie'],$_GET['id']);
	}
}


$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'biller');
$smarty -> assign('active_tab', '#people');
