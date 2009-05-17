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

$smarty->assign('preference',$preference);

$smarty -> assign('pageActive', 'preference');
$subPageActive = $_GET['action'] =="view"  ? "preferences_view" : "preferences_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#setting');
?>
