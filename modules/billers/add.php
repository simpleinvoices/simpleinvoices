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

$smarty->assign("files", $files);

#get custom field labels
$customFieldLabel = getCustomFieldLabels();

if ($_POST['name'] != "") {
	include ("./modules/billers/save.php");
}

$smarty->assign('files', $files);
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('save', $save);

$smarty -> assign('pageActive', 'biller');
$smarty -> assign('subPageActive', 'biller_add');
$smarty -> assign('active_tab', '#people');

?>
