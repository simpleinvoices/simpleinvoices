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

//error_log("cat:".$_POST['categorie']);

if ( $op === 'insert_biller') {
	
	if($id = insertBiller()) {
 		$saved = true;
 		//error_log("bbb:".mysql_insert_id());
 		saveCustomFieldValues($_POST['categorie'],mysql_insert_id());
 	}
}

if ($op === 'edit_biller' ) {
	if (isset($_POST['save_biller']) && updateBiller()) {
		$saved = true;
		updateCustomFieldValues($_POST['categorie'],$_GET['id']);
	}
}


$smarty -> assign('saved',$saved);

?>
