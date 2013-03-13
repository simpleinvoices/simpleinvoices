<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['name'] != "" ) {
	include("./modules/product_attribute/save.php");
}
$pageActive = "options";

$smarty->assign('pageActive', $pageActive);
$smarty -> assign('save',$save);



?>
