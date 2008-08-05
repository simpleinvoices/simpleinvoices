<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pageActive = "options";
$smarty->assign('pageActive', $pageActive);
	


?>
