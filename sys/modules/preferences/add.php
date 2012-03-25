<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("sys/modules/preferences/save.php");
}
$smarty -> assign('save',$save);

$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
$preferences = $SI_PREFERENCES->fetchAllActive();

$localelist = Zend_Locale::getLocaleList();

$smarty->assign('preferences',$preferences);
$smarty->assign('defaults',$defaults);
$smarty->assign('localelist',$localelist);

$smarty -> assign('pageActive', 'preference');
$smarty -> assign('subPageActive', 'preferences_add');
$smarty -> assign('active_tab', '#setting');
?>
