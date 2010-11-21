<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#get custom field labels
//if valid then do save
if ($_POST['name'] != "" ) {
	include("./extensions/expense/modules/expense_account/save.php");
}
$smarty -> assign('save',$save);

$smarty -> assign('pageActive', 'expense_account');
$smarty -> assign('subPageActive', 'add');
$smarty -> assign('active_tab', '#money');
?>
