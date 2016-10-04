<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (!empty($_POST['name'])) {
	include("./extensions/expense/modules/expense_account/save.php");
}

$smarty->assign('domain_id'    , domain_id::get());
$smarty->assign('pageActive'   , 'expense_account');
$smarty->assign('subPageActive', 'add');
$smarty->assign('active_tab'   , '#money');
