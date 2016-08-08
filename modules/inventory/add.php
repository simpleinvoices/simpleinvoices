<?php

$saved = "false";
if (!empty($_POST['op']) && $_POST['op'] =='add' && !empty($_POST['product_id'])) {
    if (Inventory::insert()) $saved = "true";
}

$product_all = Product::get_all();

$smarty->assign('product_all'  , $product_all);
$smarty->assign('saved'        , $saved);
$smarty->assign("domain_id"    , domain_id::get());

$smarty->assign('pageActive'   , 'inventory');
$smarty->assign('subPageActive', 'inventory_add');
$smarty->assign('active_tab'   , '#product');
