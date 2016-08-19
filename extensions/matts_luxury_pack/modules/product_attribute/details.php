<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['name'] != "" ) {
	include("./modules/product_attribute/save.php");
}

#get the invoice id
$id = $_GET['id'];

$sql_prod = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE id = :id;";
$sth_prod =  dbQuery($sql_prod, ':id', $id);
$product_attribute = $sth_prod->fetch();
$type = product_attributes::get($id);
$product_attribute['type'] = $type['type'];

$sql2= "SELECT id, name FROM ".TB_PREFIX."products_attribute_type";
$sth2 =  dbQuery($sql2);
$types = $sth2->fetchAll(PDO::FETCH_ASSOC);

$smarty -> assign("types", $types);


$product_attribute['wording_for_enabled'] = $product_attribute['enabled']==1?$LANG['enabled']:$LANG['disabled'];
$product_attribute['wording_for_visible'] = $product_attribute['visible']==1?$LANG['enabled']:$LANG['disabled'];
$smarty -> assign('pageActive', 'product_attributes');
$smarty -> assign('subPageActive', 'product_attribute_'.$_GET['action']);
//$pageActive = "product_attribute_manage";
//$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');

$smarty->assign('product_attribute',$product_attribute);

?>
