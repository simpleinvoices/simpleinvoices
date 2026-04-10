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

#get the invoice id
$biller_id = $_GET['id'];

$biller = getBiller($biller_id);

/*drop down list code for invoice logo */

$files = getLogoList();

/*end logo stuff */

#get custom field labels
$customFieldLabel = getCustomFieldLabels();

$bladeView->assign('biller', $biller);
/*
$bladeView -> assign('enabled', array(
                                0 => $LANG['disabled'],
				1 => $LANG['enabled']
			)
		);
 */
 
$bladeView->assign('files', $files);
$bladeView->assign('customFieldLabel', $customFieldLabel);

$bladeView -> assign('pageActive', 'biller');
$subPageActive = $_GET['action'] =="view"  ? "biller_view" : "biller_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#people');
?>
