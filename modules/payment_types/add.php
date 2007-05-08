<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['pt_description'] != "" ) {
	include("./modules/payment_types/save.php");
}

$smarty -> assign('save',$save);

?>
