<?php

checkLogin();

$files = getLogoList();

$smarty -> assign("files",$files);

#get custom field labels
$customFieldLabel = getCustomFieldLabels();

if ($_POST['name'] != "" ) {
	include("./modules/billers/save.php");
}

$smarty -> assign('files',$files);
$smarty -> assign('customFieldLabel',$customFieldLabel);
$smarty -> assign('save',$save);

?>
