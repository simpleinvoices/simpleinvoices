<?php

checkLogin();

// Deal with op and add some basic sanity checking

$saved = false;

if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
	$saved = updateDefault($_POST['name'],$_POST['value']);
}

$bladeView->assign('saved',      $saved);
$bladeView->assign('pageActive', 'system_default');
$bladeView->assign('active_tab', '#setting');
?>
