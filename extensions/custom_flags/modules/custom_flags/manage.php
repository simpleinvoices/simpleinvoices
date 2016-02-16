<?php
/*
 * Script: manage.php
 * Custom flags manage page
 *
 * Authors:
 * Richard Rowley
 *
 * Last edited:
 * 2015-09-23
 *
 * License:
 * GPL v3 or above
 */

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$path1 = './extensions/custom_flags/include';
$curr_path = get_include_path();
if (!strstr($curr_path, $path1)) {
    $path2 = $path1 . '/class';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path1 . PATH_SEPARATOR . $path2);
}

// Create & initialize DB table if it doesn't exist.
CreateCustomFlagsTable::createTable();

$cflgs = getCustomFlags();
$smarty->assign('cflgs', $cflgs);

$smarty->assign('pageActive', 'custom_flags');
$smarty->assign('active_tab', '#setting');
