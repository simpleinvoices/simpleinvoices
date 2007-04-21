<?php

checkLogin();

$files = getLogoList();

$smarty -> assign("files",$files);

#get custom field labels
$customFieldLabel = getCustomFieldLabels("biller");

include("./src/billers/save.php");

$smarty -> assign('files',$files);
$smarty -> assign('customFieldLabel',$customFieldLabel);
$smarty -> assign('save',$save);

?>
