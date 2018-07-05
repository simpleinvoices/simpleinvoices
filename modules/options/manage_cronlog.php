<?php
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$cronlogs = CronLog::select($pdoDb, domain_id::get());
$smarty -> assign("cronlogs",$cronlogs);

$smarty -> assign('pageActive', 'options');
$smarty -> assign('active_tab', '#setting');
