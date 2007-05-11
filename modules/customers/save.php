<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert customer

$saved = false;

if ($op === "insert_customer") {

	if (insertCustomer()) {
		$saved = true;
	}
}

if ( $op === 'edit_customer' ) {

	if (isset($_POST['save_customer'])) {
		
		if (updateCustomer()) {
			$saved = true;
		}
	}
}


$smarty -> assign('saved',$saved); 
?>
