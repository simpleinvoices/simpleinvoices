<?php


$get_inventory = new inventory();
$get_inventory->id = (int)$_GET['id'];
$inventory = $get_inventory->select();
si_check_record_access($inventory);

$bladeView -> assign('inventory',$inventory);
$bladeView -> assign('pageActive', 'inventory');
$bladeView -> assign('subPageActive', 'inventory_view');
$bladeView -> assign('active_tab', '#product');
