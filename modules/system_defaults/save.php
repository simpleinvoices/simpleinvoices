<?php

checkLogin();

// Deal with op and add some basic sanity checking

$saved = false;

if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
	$saved = updateDefault($_POST['name'],$_POST['value']);
}

$smarty->assign('saved',      $saved);
$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');
?>
