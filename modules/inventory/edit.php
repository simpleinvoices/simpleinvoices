<?php
global $smarty;

$saved = "false";
if (!empty($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['product_id'])) {
    if (Inventory::update()) $saved = "true";
}

$inventory = Inventory::select();
$product_all = Product::get_all();

$smarty->assign('product_all'  , $product_all);
$smarty->assign('saved'        , $saved);
$smarty->assign('inventory'    , $inventory);
$smarty->assign("domain_id"    , domain_id::get());

$smarty->assign('pageActive'   , 'inventory');

$smarty->assign('subPageActive', 'inventory_edit');
$smarty->assign('active_tab'   , '#product');
