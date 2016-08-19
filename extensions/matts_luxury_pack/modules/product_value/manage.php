<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pageActive = "product_values";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');


?>
