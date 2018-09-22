<?php
/*
 * Script: manage.php
 * Biller manage page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 2016-01-16 by Rich Rowley to add signature field
 * 2007-07-19
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
checkLogin();

$number_of_rows = Biller::count();

$smarty->assign('number_of_rows', $number_of_rows);
$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
