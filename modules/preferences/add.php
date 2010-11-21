<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("./modules/preferences/save.php");
}
$smarty -> assign('save',$save);

$defaults = getSystemDefaults();
$preferences = getActivePreferences();

$localelist = Zend_Locale::getLocaleList();

$smarty->assign('preferences',$preferences);
$smarty->assign('defaults',$defaults);
$smarty->assign('localelist',$localelist);

$smarty -> assign('pageActive', 'preference');
$smarty -> assign('subPageActive', 'preferences_add');
$smarty -> assign('active_tab', '#setting');
?>
