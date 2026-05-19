<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pageActive = "product_value_manage";
$bladeView->assign('pageActive', $pageActive);
$bladeView -> assign('active_tab', '#product');


?>
