<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$smarty -> assign("taxes",getTaxes());

$smarty -> assign('pageActive', 'tax_rate');
$smarty -> assign('active_tab', '#setting');
?>
