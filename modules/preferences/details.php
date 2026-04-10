<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("./modules/preferences/save.php");
}

#get the invoice id
$preference_id = $_GET['id'];

$preference = getPreference($preference_id);
$index_group = getPreference($preference['index_group']);

$preferences = getActivePreferences();
$defaults = getSystemDefaults();
$status = array(array('id'=>'0','status'=>$LANG['draft']), array('id'=>'1','status'=>$LANG['real']));
require_once __DIR__ . '/../../include/class/LocaleHelper.php';
$localelist = LocaleHelper::getLocaleList();

$bladeView->assign('preference',$preference);
$bladeView->assign('defaults',$defaults);
$bladeView->assign('index_group',$index_group);
$bladeView->assign('preferences',$preferences);
$bladeView->assign('status',$status);
$bladeView->assign('localelist',$localelist);

$bladeView -> assign('pageActive', 'preference');
$subPageActive = $_GET['action'] =="view"  ? "preferences_view" : "preferences_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#setting');
?>
