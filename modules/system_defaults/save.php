<?php

checkLogin();

# Deal with op and add some basic sanity checking

$saved = false;


error_log($_POST['name']."  ".$_POST['value']);

//echo $_POST['value']."VAL";
if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
	
	if(updateDefault($_POST['name'],$_POST['value'])) {
		$saved = true;
	}
}
$pageActive = "options";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign("saved",$saved);
