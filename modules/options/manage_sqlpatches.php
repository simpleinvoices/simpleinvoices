<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$bladeView -> assign("patches",getSQLPatches());

$bladeView -> assign('pageActive', 'sqlpatch');
$bladeView -> assign('active_tab', '#setting');
?>