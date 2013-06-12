<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['name'] != "" ) {
	include("./extensions/product_matrix/modules/product_attribute/save.php");
}

#get the invoice id
$id = $_GET['id'];

$sql_prod = "select * from ".TB_PREFIX."products_attributes where id = $id;";
$sth_prod =  dbQuery($sql_prod);
$product_attribute = $sth_prod->fetch();
$type = product_attributes::get($id);
$product_attribute['type'] = $type['type'];

$sql2= "select id, name from ".TB_PREFIX."products_attribute_type";
$sth2 =  dbQuery($sql2);
$types = $sth2->fetchAll(PDO::FETCH_ASSOC);

$smarty -> assign("types", $types);


$product_attribute['wording_for_enabled'] = $product_attribute['enabled']==1?$LANG['enabled']:$LANG['disabled'];
$product_attribute['wording_for_visible'] = $product_attribute['visible']==1?$LANG['enabled']:$LANG['disabled'];
$pageActive = "options";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('product_attribute',$product_attribute);

?>
