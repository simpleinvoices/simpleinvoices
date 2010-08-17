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

$smarty->assign('biller', $biller);
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
