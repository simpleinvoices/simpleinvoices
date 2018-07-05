<?php
/*
 * Script: itemised.php
 *   Itemized invoice page
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2016-07-23
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   http://www.simpleinvoices.org
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include('./modules/invoices/invoice.php');

$smarty -> assign('pageActive', 'invoice_new');
$smarty -> assign('subPageActive', 'invoice_new_itemised');
$smarty -> assign('active_tab', '#money');
