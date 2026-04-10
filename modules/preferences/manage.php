<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$preferences = getPreferences();

$bladeView -> assign("preferences",$preferences);

$bladeView -> assign('pageActive', 'preference');
$bladeView -> assign('active_tab', '#setting');
?>