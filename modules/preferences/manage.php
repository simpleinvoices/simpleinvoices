<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$preferences = getPreferences();

$pageActive = "options";

$smarty->assign('pageActive', $pageActive);
$smarty -> assign("preferences",$preferences);
	
getRicoLiveGrid("rico_preferences","{ type:'number', decPlaces:0, ClassName:'alignleft' }");


?>
