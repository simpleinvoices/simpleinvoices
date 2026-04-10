<?php



if ($_POST['op'] =='edit' AND !empty($_POST['product_id']))
{
    $saved = "false";

	$inventory = new inventory();
	$inventory->id=$_GET['id'];
	$inventory->domain_id=domain_id::get();
	$inventory->product_id=$_POST['product_id'];
	$inventory->quantity=$_POST['quantity'];
	$inventory->cost=$_POST['cost'];
	$inventory->date=$_POST['date'];
	$inventory->note=$_POST['note'];
	$result = $inventory->update();

	$saved = !empty($result) ? "true" : "false";
}      

$invoices = new invoice();
$invoices->sort='id';
$invoice_all = $invoices->select_all('count');

$get_inventory = new inventory();
$get_inventory->id = $_GET['id'];
$inventory = $get_inventory->select();

$productobj = new product();
$product_all = $productobj->get_all();
$bladeView -> assign('product_all',$product_all);
$bladeView -> assign('saved',$saved);
$bladeView -> assign('inventory',$inventory);

$bladeView -> assign('pageActive', 'inventory');
$bladeView -> assign('subPageActive', 'inventory_edit');
$bladeView -> assign('active_tab', '#product');
