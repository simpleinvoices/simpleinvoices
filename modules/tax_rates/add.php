<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['tax_description'] != "" ) { 
	include("./modules/tax_rates/save.php");
}

$types = getTaxTypes();

$bladeView -> assign("types",$types);

$bladeView -> assign('save',$save);

$bladeView -> assign('pageActive', 'tax_rate');
$bladeView -> assign('subPageActive', 'tax_rate_add');
$bladeView -> assign('active_tab', '#setting');
?>
