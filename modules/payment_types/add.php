<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$bladeView -> assign('pageActive', 'payment_type');
$bladeView -> assign('subPageActive', 'payment_types_add');
$bladeView -> assign('active_tab', '#setting');
?>
