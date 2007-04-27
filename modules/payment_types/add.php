<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


include("./src/payment_types/save.php");

$smarty -> assign('save',$save);


//include("./templates/default/payment_types/add2.tpl");
//echo $block;

?>
