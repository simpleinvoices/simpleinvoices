<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert biller

$saved = false;

if ( $op === 'insert_biller') {
	
	if(insertBiller()) {
 		$saved = true;
 	}
}

if ($op === 'edit_biller' ) {
	if (isset($_POST['save_biller']) && updateBiller()) {
		$saved = true;
	}
}


$smarty -> assign('saved',$saved);

?>
