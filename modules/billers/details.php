<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$biller_id = $_GET['submit'];

$biller = getBiller($biller_id);


/*drop down list code for invoice logo */

$files = getLogoList();

/*end logo stuff */

#get custom field labels
$customFieldLabel = getCustomFieldLabels();

$smarty -> assign('biller',$biller);
/*
$smarty -> assign('enabled', array(
                                0 => $LANG[disabled],
				1 => $LANG[enabled]
			)
		);
 */
$smarty -> assign('files',$files);
$smarty -> assign('customFieldLabel',$customFieldLabel);

?>
