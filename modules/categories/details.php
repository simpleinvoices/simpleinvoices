<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#table

/*jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("tax_description",$LANG['tax_description']);
jsValidateifNum("tax_percentage",$LANG['tax_percentage']);
jsFormValidationEnd();
jsEnd();*/



#get the invoice id
$category_id = $_GET['id'];

$category = getCategory($category_id);

$cat_selected = getCatys($category_id);

$categories = categories::get_cats();

$smarty -> assign('category',$category);
$smarty -> assign('cat_selected',$cat_selected);
$smarty -> assign('categories',$categories);

$smarty -> assign('pageActive', 'categories_manage');
$subPageActive = $_GET['action'] =="view"  ? "categories_view" : "categories_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#product');
?>
