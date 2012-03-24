<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PRODUCTS = new SimpleInvoices_Products();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product
$saved = false;

if (  $op === 'insert_product' ) {

    $data = array(
        'description'       => $_POST['description'],
        'detail'            => $_POST['detail'],
        'unit_price'        => $_POST['unit_price'],
        'default_tax_id'    => $_POST['default_tax_id'],
        'default_tax_id_2'  => NULL,
        'cost'              => $_POST['cost'],
        'reorder_level'     => $_POST['reoder_level'],
        'custom_field1'     => $_POST['custom_field1'],
        'custom_field2'     => $_POST['custom_field2'],
        'custom_field3'     => $_POST['custom_field3'],
        'custom_field4'     => $_POST['custom_field4'],
        'notes'             => $_POST['notes'],
        'enabled'           => 1,
        'visible'           => 1
    );
    
	if($id = $SI_PRODUCTS->insert($data)) {
 		$saved = true;
 		//saveCustomFieldValues($_POST['categorie'], lastInsertId());
 	}
}

if ($op === 'edit_product' ) {
    
    $product_data = array(
        'description'       => $_POST['description'],
        'detail'            => $_POST['detail'],
        'unit_price'        => $_POST['unit_price'],
        'default_tax_id'    => $_POST['default_tax_id'],
        /*'default_tax_id_2'  => NULL,*/
        'cost'              => $_POST['cost'],
        'reorder_level'     => $_POST['reoder_level'],
        'custom_field1'     => $_POST['custom_field1'],
        'custom_field2'     => $_POST['custom_field2'],
        'custom_field3'     => $_POST['custom_field3'],
        'custom_field4'     => $_POST['custom_field4'],
        'notes'             => $_POST['notes'],
        'enabled'           => $_POST['enabled']
        /*'visible'           => $visible*/
    );
    
	if (isset($_POST['save_product']) && $SI_PRODUCTS->update($product_data,$_GET['id'])) {
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