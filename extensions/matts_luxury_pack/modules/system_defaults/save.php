<?php

checkLogin();

# Deal with op and add some basic sanity checking

$saved = false;

error_log ($_POST['name']."  ".$_POST['value']);

//echo $_POST['value']."VAL";
if (isset ($_POST['op']) && $_POST['op'] == 'update_system_defaults') {
	
	if (updateDefault ($_POST['name'], $_POST['value'])) {
		$saved = true;
	}
}
$smarty->assign("saved", $saved);

$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');
