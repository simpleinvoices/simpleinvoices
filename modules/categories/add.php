<?php


/*if ($_POST['op'] =='insert_categories' AND !empty($_POST['parent']))
{
	$category = new categories();
	$category->domain_id=domain_id::get();
	$category->name=$_POST['name'];
	$category->slug=$_POST['parent'];
	$category->referencia=$_POST['referencia'];
	$result = $category->insert();

	$saved = !empty($result) ? "true" : "false";
} */

include("./modules/categories/save.php");     

$categories = categories::get_cats();

$smarty -> assign('categories',$categories);
$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'categories_add');
$smarty -> assign('active_tab', '#product');
