<?php
/*
* Script: /simple/extensions/customer_add_tabbed/modules/customers/save.php
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

$op = !empty ($_POST['op']) ? addslashes ($_POST['op']) : NULL;

$defaults = getSystemDefaults();
#insert customer

$saved = false;

//	echo "<script>alert('defaults=".print_r ($defaults,true).",defaults price_list=".$defaults['price_list']."|')</script>";

if ($op === "insert_customer") {

	if ($defaults['price_list'] && insert_Customer()) {// do insert_Customer() if $defaults['price_list'] enabled
		$saved = true;
		// saveCustomFieldValues($_POST['categorie'],lastInsertId());
	} else
	if (insertCustomer()) {// otherwise do insertCustomer() - as per core
		$saved = true;
		// saveCustomFieldValues($_POST['categorie'],lastInsertId());
	}
}

if ($op === 'edit_customer') {

	if (isset ($_POST['save_customer'])) {
		
		if ($defaults['price_list'] && update_Customer()) {// do update_Customer() if $defaults['price_list'] enabled
			$saved = true;
			//updateCustomFieldValues($_POST['categorie'],$_GET['customer']);
		} else
		if (updateCustomer()) {// otherwise do updateCustomer() - as per core
			$saved = true;
			// saveCustomFieldValues($_POST['categorie'],lastInsertId());
		}
	}
}

$smarty -> assign('saved',$saved); 
$smarty -> assign('last_id',$_POST['last_id']);
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('active_tab', '#people');
?>
