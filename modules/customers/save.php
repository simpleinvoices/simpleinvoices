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

$op = $_POST['op'] ?? null;

#insert customer

$saved = false;
$save_error = null;

if ($op === "insert_customer") {

	if (insertCustomer()) {
		$saved = true;
		invoice_denorm::refreshAllForCustomer((int) lastInsertId());
	} elseif (customerNameExists(trim((string) ($_POST['name'] ?? '')))) {
		$save_error = 'duplicate_customer_name';
	}
}

if ( $op === 'edit_customer' ) {

	if (isset($_POST['save_customer'])) {
		
		if (updateCustomer()) {

			$saved = true;
			invoice_denorm::refreshAllForCustomer((int) $_GET['customer']);
		} elseif (customerNameExists(trim((string) ($_POST['name'] ?? '')), (int) ($_GET['id'] ?? 0))) {
			$save_error = 'duplicate_customer_name';
		}
	}
}

$bladeView -> assign('saved',$saved);
$bladeView -> assign('save_error', $save_error);

$bladeView -> assign('pageActive', 'customer');
$bladeView -> assign('active_tab', '#people');
?>
