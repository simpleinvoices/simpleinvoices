<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['name'] != "" ) {
	include("./modules/product_attribute/save.php");
}

$sql2= "SELECT id, name FROM ".TB_PREFIX."products_attribute_type";
$sth2 =  $db->query($sql2);
$types = $sth2->fetchAll(PDO::FETCH_ASSOC);

$smarty -> assign("types", $types);

$pageActive = "product_attribute_add";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');

$smarty -> assign('save',$save);



?>
