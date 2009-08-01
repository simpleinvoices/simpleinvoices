<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$number_of_rows  = expense::count();

$smarty -> assign("number_of_rows",$number_of_rows);

$smarty -> assign('pageActive', 'expense');
$smarty -> assign('active_tab', '#money');
?>
