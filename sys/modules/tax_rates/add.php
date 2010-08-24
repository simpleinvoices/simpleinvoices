<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['tax_description'] != "" ) { 
	include("./modules/tax_rates/save.php");
}

$types = getTaxTypes();

$smarty -> assign("types",$types);

$smarty -> assign('save',$save);

$smarty -> assign('pageActive', 'tax_rate');
$smarty -> assign('subPageActive', 'tax_rate_add');
$smarty -> assign('active_tab', '#setting');
?>
