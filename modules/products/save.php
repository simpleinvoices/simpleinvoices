<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product

if (  $op === 'insert_product' ) {

$sql = "INSERT into
		".TB_PREFIX."products
	VALUES
		(	
			'',
			'$_POST[description]',
			'$_POST[unit_price]',
			'$_POST[custom_field1]',
			'$_POST[custom_field2]',
			'$_POST[custom_field3]',
			'$_POST[custom_field4]',
			'$_POST[notes]',
			'$_POST[enabled]',
			'1'
		)";

if (mysqlQuery($sql, $conn)) {
	$display_block = $LANG['save_product_success'];
} else {
	$display_block = $LANG['save_product_failure'];
}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=products&view=manage>";
}



#edit product

else if (  $op === 'edit_product' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

	if (isset($_POST['save_product'])) {
		$sql = "UPDATE ".TB_PREFIX."products
			SET
				description = '$_POST[description]',
				enabled = '$_POST[enabled]',
				notes = '$_POST[notes]',
				custom_field1 = '$_POST[custom_field1]',
				custom_field2 = '$_POST[custom_field2]',
				custom_field3 = '$_POST[custom_field3]',
				custom_field4 = '$_POST[custom_field4]',
				unit_price = '$_POST[unit_price]'
			WHERE
				id = '$_GET[submit]'";

		if (mysqlQuery($sql, $conn)) {
			$display_block = $LANG['save_product_success'];
		} else {
			$display_block = $LANG['save_product_failure'];
		}

		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";
		}

	else if (isset($_POST['cancel'])) {
	
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";
	}
}


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 

?>
