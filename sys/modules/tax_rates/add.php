<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$save = false;

//if valid then do save
if( isset($_POST['tax_description'])) {
    if ($_POST['tax_description'] != "" ) {
	    include("sys/modules/tax_rates/save.php");
    }
}

$types = array(
    '%' => '%',
    '$' => '$'
);

$smarty -> assign("types",$types);
$smarty -> assign('save',$save);
$smarty -> assign('pageActive', 'tax_rate');
$smarty -> assign('subPageActive', 'tax_rate_add');
$smarty -> assign('active_tab', '#setting');
?>