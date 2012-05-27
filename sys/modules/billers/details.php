<?php

/*
* Script: details.php
* 	Biller details page
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

if (!isset($_GET['id'])) {
	throw new SimpleInvoices_Exception('Invalid biller id');
}

$biller = new SimpleInvoices_Biller($_GET['id']);

/*drop down list code for invoice logo */

$files = SimpleInvoices_Biller::getLogoList();

/*end logo stuff */

#get custom field labels
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();
$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();

$smarty->assign('biller', $biller->toArray());
/*
$smarty -> assign('enabled', array(
                                0 => $LANG[disabled],
				1 => $LANG[enabled]
			)
		);
 */

$smarty->assign('files', $files);
$smarty->assign('customFieldLabel', $customFieldLabel);

$smarty -> assign('pageActive', 'biller');
$subPageActive = $_GET['action'] =="view"  ? "biller_view" : "biller_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#people');
?>