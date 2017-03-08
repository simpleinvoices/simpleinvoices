<?php
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$expense_account = ExpenseAccount::select($_GET['id']);

$smarty->assign('expense_account',$expense_account);
$smarty->assign('pageActive', 'expense_account');

$subPageActive = ($_GET['action'] == "view"  ? "view" : "edit");

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#money');
