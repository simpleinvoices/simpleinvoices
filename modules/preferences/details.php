<?php
global $smarty, $LANG;
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (isset($_POST['p_description']) && $_POST['p_description'] != "" ) {
	include("modules/preferences/save.php");
}

$preference_id = $_GET['id'];

$preference = Preferences::getPreference($preference_id);
$index_group = Preferences::getPreference($preference['index_group']);

$preferences = Preferences::getActivePreferences();
$defaults = getSystemDefaults();
$status = array(array('id'=>'0','status'=>$LANG['draft']), array('id'=>'1','status'=>$LANG['real']));
$localelist = Zend_Locale::getLocaleList();

$smarty->assign('preference',$preference);
$smarty->assign('defaults',$defaults);
$smarty->assign('index_group',$index_group);
$smarty->assign('preferences',$preferences);
$smarty->assign('status',$status);
$smarty->assign('localelist',$localelist);

$smarty->assign('pageActive', 'preference');

$subPageActive = $_GET['action'] =="view"  ? "preferences_view" : "preferences_edit" ;
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#setting');

