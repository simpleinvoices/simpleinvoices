<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product

if (  $op === 'insert_product' ) {

$sql = "INSERT into
		{$tb_prefix}products
	VALUES
		(	
			'',
			'$_POST[prod_description]',
			'$_POST[prod_unit_price]',
			'$_POST[prod_custom_field1]',
			'$_POST[prod_custom_field2]',
			'$_POST[prod_custom_field3]',
			'$_POST[prod_custom_field4]',
			'$_POST[prod_notes]',
			'$_POST[prod_enabled]'
		)";

if (mysql_query($sql, $conn)) {
	$display_block = $LANG['save_product_success'];
} else {
	$display_block = $LANG['save_product_failure'];
}

	//header( 'refresh: 2; url=manage_products.php' );
	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=products&view=manage>";
}



#edit product

else if (  $op === 'edit_product' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

	if (isset($_POST['save_product'])) {
		$sql = "UPDATE
				{$tb_prefix}products
			SET
				prod_description = '$_POST[prod_description]',
				prod_enabled = '$_POST[prod_enabled]',
				prod_notes = '$_POST[prod_notes]',
				prod_custom_field1 = '$_POST[prod_custom_field1]',
				prod_custom_field2 = '$_POST[prod_custom_field2]',
				prod_custom_field3 = '$_POST[prod_custom_field3]',
				prod_custom_field4 = '$_POST[prod_custom_field4]',
				prod_unit_price = '$_POST[prod_unit_price]'
			WHERE
				prod_id = '$_GET[submit]'";

		if (mysql_query($sql, $conn)) {
			$display_block = $LANG['save_product_success'];
		} else {
			$display_block = $LANG['save_product_failure'];
		}

		//header( 'refresh: 2; url=manage_products.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";


		}

	else if (isset($_POST['cancel'])) {
	
		//header( 'refresh: 0; url=manage_products.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";
	}
}


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 

?>
