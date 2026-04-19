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

$op = $_POST['op'] ?? null;

#insert biller

$saved = false;

if ( $op === 'insert_biller') {
	
	if (insertBiller()) {
 		$saved = true;
 		invoice_denorm::refreshAllForBiller((int) lastInsertId());
 	}
}

if ($op === 'edit_biller' ) {
	if (isset($_POST['save_biller']) && updateBiller()) {
		$saved = true;
		invoice_denorm::refreshAllForBiller((int) $_GET['id']);
	}
}


$bladeView -> assign('saved',$saved);

$bladeView -> assign('pageActive', 'biller');
$bladeView -> assign('active_tab', '#people');
