<?php

/*
 * Script: details.php
 * Biller details page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 2007-07-19
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * http://www.simpleinvoices.org
 */

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$biller_id = $_GET['id'];
$biller = getBiller($biller_id);

// Drop down list code for invoice logo
$files = getLogoList();
$smarty->assign('files', $files);

$customFieldLabel = getCustomFieldLabels('', true);
$smarty->assign('customFieldLabel', $customFieldLabel);
// @formatter:off
$smarty->assign('biller'    , $biller);
$smarty->assign('pageActive', 'biller');

$subPageActive = $_GET['action'] == "view" ? "biller_view" : "biller_edit";
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab'   , '#people');
// @formatter:on
