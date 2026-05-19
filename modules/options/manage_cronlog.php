<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$get_cronlog = new cronlog();
$cronlogs = $get_cronlog->select();

$bladeView -> assign("cronlogs",$cronlogs);

$bladeView -> assign('pageActive', 'options');
$bladeView -> assign('active_tab', '#setting');
?>