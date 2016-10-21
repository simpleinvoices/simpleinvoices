<?php
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$smarty -> assign("patches",getSQLPatches());

$smarty -> assign('pageActive', 'sqlpatch');
$smarty -> assign('active_tab', '#setting');
