<?php


$get_inventory = new inventory();
$get_inventory->id = $_GET['id'];
$inventory = $get_inventory->select();

$smarty -> assign('inventory',$inventory);
$smarty -> assign('pageActive', 'inventory');
$smarty -> assign('subPageActive', 'inventory_view');
$smarty -> assign('active_tab', '#product');
