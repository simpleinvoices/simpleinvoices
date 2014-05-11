<?php
/*
* Script: save.php
* 	Customers save page
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

#insert customer

$saved = false;

$sub_customerobj = new sub_customer();

if ($op === "insert_customer") {

	if ($sub_customerobj->insertCustomer()) {
		$saved = true;
		// saveCustomFieldValues($_POST['categorie'],$db->lastInsertId());
	}
}

if ( $op === 'edit_customer' ) {

	if (isset($_POST['save_customer'])) {
		
		if ($sub_customerobj->updateCustomer()) {

			$saved = true;
			//updateCustomFieldValues($_POST['categorie'],$_GET['customer']);
		}
	}
}

$smarty -> assign('saved',$saved); 

$smarty -> assign('pageActive', 'customer');
$smarty -> assign('active_tab', '#people');
?>
