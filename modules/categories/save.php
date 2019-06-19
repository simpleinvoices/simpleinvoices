<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product
$saved = false;

if (  $op === 'insert_categories' ) {
	
	if($id = insertCategories()) {
 		$saved = true;
 		insert_categories_parent();
 	}
}

if ($op === 'edit_category' ) {
	if (isset($_POST['save_category']) && updateCategories()) {
		$saved = true;
		update_categories_parent($_GET['id']);
	}
}




$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';


$smarty->assign('saved',$saved);
//$smarty -> assign('display_block',$display_block); 
//$smarty -> assign('refresh_total',$refresh_total); 

$smarty -> assign('pageActive', 'categories_manage');
$smarty -> assign('active_tab', '#product');
?>
