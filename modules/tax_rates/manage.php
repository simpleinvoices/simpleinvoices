<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$bladeView -> assign("taxes",getTaxes());

$bladeView -> assign('pageActive', 'tax_rate');
$bladeView -> assign('active_tab', '#setting');
?>
