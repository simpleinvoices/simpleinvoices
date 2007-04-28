<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['tax_description'] != "" ) { 
	include("./modules/tax_rates/save.php");
}

$smarty -> assign('save',$save);
?>
