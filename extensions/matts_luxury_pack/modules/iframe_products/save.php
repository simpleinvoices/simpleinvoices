<?php

// /simple/extensions/product_add_LxWxH_weight/modules/products

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty ($_POST['op']) ? addslashes ($_POST['op']) : NULL;

$defaults = getSystemDefaults();
#insert product
$saved = false;

if ($op === 'insert_product') {
	
	if ($defaults['price_list']) {
		if ($id = insert_Product()) // do insert_Product() if price_list enabled
			$saved = true;
		// saveCustomFieldValues($_POST['categorie'],lastInsertId());
	} else
	if (!$defaults['price_list']) {
		if ($id = insertProduct()) // otherwise do insertProduct() - as per core
		$saved = true;
		// saveCustomFieldValues($_POST['categorie'],lastInsertId());
	}
}

if (isset ($_POST['save_product']) && $op === 'edit_product') {

//	echo "<script>alert('defaults=".print_r ($defaults,true).",defaults price_list=".$defaults['price_list']."|')</script>";

	if ($defaults['price_list'] && $id = update_Product()) {// do update_Product() if price_list enabled
		$saved = true;
		// saveCustomFieldValues($_POST['categorie'],lastInsertId());
	} else
	if (!$defaults['price_list'] && $id = updateProduct()) {// otherwise do updateProduct() - as per core
		$saved = true;
		// saveCustomFieldValues($_POST['categorie'],lastInsertId());
	}
}

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty->assign('saved',$saved);
//$smarty -> assign('display_block',$display_block); 
//$smarty -> assign('refresh_total',$refresh_total); 

$smarty -> assign('pageActive', 'product_manage');
$smarty -> assign('active_tab', '#product');
?>
