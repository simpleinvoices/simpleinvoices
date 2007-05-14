<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert payment type

if (  $op === 'insert_payment_type' ) {

/*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
$sql = "INSERT INTO {$tb_prefix}tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
*/

$sql = "INSERT into
		{$tb_prefix}payment_types
	VALUES
		(	
			'',
			'$_POST[pt_description]',
			'$_POST[pt_enabled]'
		)";

if (mysqlQuery($sql, $conn)) {
	$display_block = $LANG['save_payment_type_success'];
} else {
	$display_block =  $LANG['save_payment_type_failure'];
}

//header( 'refresh: 2; url=manage_payment_types.php' );
$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=payment_types&view=manage>";


}


#edit payment type

else if (  $op === 'edit_payment_type' ) {

	$conn = mysql_connect("$db_host","$db_user","$db_password");
	mysql_select_db("$db_name",$conn);

	if (isset($_POST['save_payment_type'])) {
		$sql = "UPDATE
				{$tb_prefix}payment_types
			SET
				pt_description = '$_POST[pt_description]',
				pt_enabled = '$_POST[pt_enabled]'
			WHERE
				pt_id = " . $_GET['submit'];

		if (mysqlQuery($sql, $conn)) {
			$display_block = $LANG['save_payment_type_success'];
		} else {
			$display_block =  $LANG['save_payment_type_failure'];
		}

		//header( 'refresh: 2; url=manage_payment_types.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=payment_types&view=manage>";

		}

	else if (isset($_POST['cancel'])) {

		//header( 'refresh: 0; url=manage_payment_types.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=payment_types&view=manage>";

	}
}

//TODO: Make redirection with php..


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';


$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 
?>
