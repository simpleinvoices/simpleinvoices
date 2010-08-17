<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$preferences = getPreferences();

$smarty -> assign("preferences",$preferences);

$smarty -> assign('pageActive', 'preference');
$smarty -> assign('active_tab', '#setting');
?>