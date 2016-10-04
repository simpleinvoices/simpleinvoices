<?php

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();
ModifyExpenseTables::modifyTables();

$number_of_rows = Expense::count();
$smarty->assign ( "number_of_rows", $number_of_rows );

if (isset ( $_GET ['query'] )) {
    $where = "&query=" . $_GET ['query'] . "&qtype=" . $_GET ['qtype'];
} else {
    $where = "";
}
$url = "index.php?module=expense&view=xml" . $where;
$smarty->assign ( 'url', $url );

$smarty->assign ( 'pageActive', 'expense' );
$smarty->assign ( 'active_tab', '#money' );
