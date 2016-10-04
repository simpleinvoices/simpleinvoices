<?php
global $smarty;

if (!empty($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['product_id'])) {
    $saved = (Inventory::update() ? "true" : "false");
    $smarty->assign('saved', $saved);
}

$inventory = Inventory::select();
$product_all = Product::select_all();

$smarty->assign('product_all'  , $product_all);
$smarty->assign('inventory'    , $inventory);
$smarty->assign("domain_id"    , domain_id::get());

$smarty->assign('pageActive'   , 'inventory');

$smarty->assign('subPageActive', 'inventory_edit');
$smarty->assign('active_tab'   , '#product');
