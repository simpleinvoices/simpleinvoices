<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("./modules/preferences/save.php");
}

#get the invoice id
$preference_id = $_GET['submit'];


$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $preference_id";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

$preference = mysql_fetch_array($result_print_preferences);

$preference['wording_for_enabled'] = $preference['pref_enabled']==1?$LANG['enabled']:$LANG['disabled'];

$smarty->assign('preference',$preference);

?>
