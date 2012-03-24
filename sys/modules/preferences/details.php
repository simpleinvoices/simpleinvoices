<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_SystemDefaults();

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("sys/modules/preferences/save.php");
}

#get the invoice id
$preference_id = $_GET['id'];

$preference = getPreference($preference_id);
$index_group = getPreference($preference['index_group']);

$preferences = getActivePreferences();
$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
$status = array(array('id'=>'0','status'=>$LANG['draft']), array('id'=>'1','status'=>$LANG['real']));

$smarty->assign('preference',$preference);
$smarty->assign('defaults',$defaults);
$smarty->assign('index_group',$index_group);
$smarty->assign('preferences',$preferences);
$smarty->assign('status',$status);

$smarty -> assign('pageActive', 'preference');
$subPageActive = $_GET['action'] =="view"  ? "preferences_view" : "preferences_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#setting');
