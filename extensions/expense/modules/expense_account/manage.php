<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$expenseaccountobj = new expenseaccount();

$number_of_rows  = $expenseaccountobj->count();

$smarty -> assign("number_of_rows",$number_of_rows);

$smarty -> assign('pageActive', 'expense_account');
$smarty -> assign('active_tab', '#money');
?>
