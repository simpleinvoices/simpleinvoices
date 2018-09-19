<?php
/*
 *  Script: save.php
 *      Customers save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-07-27
 *
 *  License:
 *      GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

// Deal with op and add some basic sanity checking
$op = ! empty ( $_POST ['op'] ) ? addslashes ( $_POST ['op'] ) : NULL;

$saved = false;
if ($op === "insert_customer") {
    $saved = SubCustomers::insertCustomer();
} else if ($op === 'edit_customer' && isset($_POST['save_customer'])) {
    $saved = SubCustomers::updateCustomer();
}

$smarty->assign ( 'saved', $saved );
$smarty->assign ( 'pageActive', 'customer' );
$smarty->assign ( 'active_tab', '#people' );
