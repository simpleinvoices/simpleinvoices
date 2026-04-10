<?php


$get_inventory = new inventory();
$get_inventory->id = $_GET['id'];
$inventory = $get_inventory->select();

$bladeView -> assign('inventory',$inventory);
$bladeView -> assign('pageActive', 'inventory');
$bladeView -> assign('subPageActive', 'inventory_view');
$bladeView -> assign('active_tab', '#product');
