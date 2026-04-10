<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = $_POST['op'] ?? null;


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


$bladeView->assign('saved',$saved);
//$bladeView -> assign('display_block',$display_block); 
//$bladeView -> assign('refresh_total',$refresh_total); 

$bladeView -> assign('pageActive', 'product_manage');
$bladeView -> assign('active_tab', '#product');
?>
