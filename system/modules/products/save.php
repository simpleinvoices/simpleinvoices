<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product
$saved = false;

if (  $op === 'insert_product' ) {
	
	if($id = insertProduct()) {
 		$saved = true;
 		//saveCustomFieldValues($_POST['categorie'], lastInsertId());
 	}
}

if ($op === 'edit_product' ) {
	if (isset($_POST['save_product']) && updateProduct()) {
		$saved = true;
		//updateCustomFieldValues($_POST['categorie'],$_GET['id']);
	}
}




$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';


$smarty->assign('saved',$saved);
//$smarty -> assign('display_block',$display_block); 
//$smarty -> assign('refresh_total',$refresh_total); 

$smarty -> assign('pageActive', 'product_manage');
$smarty -> assign('active_tab', '#product');
?>
