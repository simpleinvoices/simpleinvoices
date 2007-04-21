<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$customFieldLabel = getCustomFieldLabels("customer");

#get custom field labels

$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty -> assign('save',$save);
include("./src/customers/save.php");
?>
