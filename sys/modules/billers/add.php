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

$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

$files = SimpleInvoices_Biller::getLogoList();

$smarty->assign("files", $files);

#get custom field labels
$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();

if (isset($_POST['name']) && $_POST['name'] != "") {
	include("sys/modules/billers/save.php");
}

$smarty->assign('files', $files);
$smarty->assign('customFieldLabel', $customFieldLabel);
if (isset($save)) {
    $smarty->assign('save', $save);
} else {
    $smarty->assign('save', '');
}

$smarty -> assign('pageActive', 'biller');
$smarty -> assign('subPageActive', 'biller_add');
$smarty -> assign('active_tab', '#people');
?>