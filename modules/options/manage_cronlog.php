<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$get_cronlog = new cronlog();
$cronlogs = $get_cronlog->select();

$smarty -> assign("cronlogs",$cronlogs);

$smarty -> assign('pageActive', 'options');
$smarty -> assign('active_tab', '#setting');
