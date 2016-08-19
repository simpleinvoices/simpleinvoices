<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$smarty -> assign('pageActive', 'product_attributes');
//$smarty -> assign('subPageActive', 'product_attribute_'.$_GET['action']);
//$pageActive = "product_attribute_manage";
//$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');


?>
