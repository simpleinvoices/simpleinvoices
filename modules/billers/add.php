<?php

/*
* Script: add.php
* 	Billers add page
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

checkLogin();

$files = getLogoList();

$bladeView->assign("files", $files);

#get custom field labels
$customFieldLabel = getCustomFieldLabels();

if ($_POST['name'] != "") {
	include ("./modules/billers/save.php");
}

$bladeView->assign('files', $files);
$bladeView->assign('customFieldLabel', $customFieldLabel);
$bladeView->assign('save', $save);

$bladeView -> assign('pageActive', 'biller');
$bladeView -> assign('subPageActive', 'biller_add');
$bladeView -> assign('active_tab', '#people');

?>
