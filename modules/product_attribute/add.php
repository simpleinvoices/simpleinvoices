<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['name'] != "" ) {
	include("./modules/product_attribute/save.php");
}
$pageActive = "options";

$sql2= "select id, name from ".TB_PREFIX."products_attribute_type";
$sth2 =  dbQuery($sql2);
$types = $sth2->fetchAll(PDO::FETCH_ASSOC);

$smarty -> assign("types", $types);

$smarty->assign('pageActive', $pageActive);
$smarty -> assign('save',$save);



?>
