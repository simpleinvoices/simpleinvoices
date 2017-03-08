<?php
global $smarty;

$inventory = Inventory::select();

$smarty -> assign('inventory',$inventory);
$smarty -> assign('pageActive', 'inventory');
$smarty -> assign('subPageActive', 'inventory_view');
$smarty -> assign('active_tab', '#product');
