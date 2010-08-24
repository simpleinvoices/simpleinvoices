<?php


if ($_POST['op'] =='add' AND !empty($_POST['product_id']))
{
	$inventory = new inventory();
	$inventory->domain_id=domain_id::get();
	$inventory->product_id=$_POST['product_id'];
	$inventory->quantity=$_POST['quantity'];
	$inventory->cost=$_POST['cost'];
	$inventory->date=$_POST['date'];
	$inventory->note=$_POST['note'];
	$result = $inventory->insert();

	$saved = !empty($result) ? "true" : "false";
}      

$product_all = product::get_all();

$smarty -> assign('product_all',$product_all);
$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'inventory');
$smarty -> assign('subPageActive', 'inventory_add');
$smarty -> assign('active_tab', '#product');
