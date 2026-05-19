<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = $_POST['op'] ?? null;


#insert product
$saved = false;
$save_error = null;

if (  $op === 'insert_product' ) {
	
	if($id = insertProduct()) {
 		$saved = true;
 		//saveCustomFieldValues($_POST['categorie'], lastInsertId());
 	} elseif (productDescriptionExists(trim((string) ($_POST['description'] ?? '')))) {
		$save_error = 'duplicate_product_description';
	}
}

if ($op === 'edit_product' ) {
	if (isset($_POST['save_product'])) {
		if (updateProduct()) {
			$saved = true;
			//updateCustomFieldValues($_POST['categorie'],$_GET['id']);
		} elseif (productDescriptionExists(trim((string) ($_POST['description'] ?? '')), (int) ($_GET['id'] ?? 0))) {
			$save_error = 'duplicate_product_description';
		}
	}
}




$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';


$bladeView->assign('saved',$saved);
$bladeView->assign('save_error', $save_error);
//$bladeView -> assign('display_block',$display_block); 
//$bladeView -> assign('refresh_total',$refresh_total); 

$bladeView -> assign('pageActive', 'product_manage');
$bladeView -> assign('active_tab', '#product');
?>
