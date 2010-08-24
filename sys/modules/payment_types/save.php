<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert payment type

if (  $op === 'insert_payment_type' ) {

/*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
$sql = "INSERT INTO ".TB_PREFIX."tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
*/

	if ($db_server == 'pgsql') {
		$sql = "INSERT into ".TB_PREFIX."payment_types
				(domain_id, pt_description, pt_enabled)
			VALUES
				(:domain_id', :description, :enabled)";
	} else {
		$sql = "INSERT into
				".TB_PREFIX."payment_types
			VALUES
				(NULL, :domain_id, :description, :enabled)";
	}
	
	if (dbQuery($sql, ':domain_id', $auth_session->domain_id, ':description', $_POST['pt_description'], ':enabled', $_POST['pt_enabled'])) {
		$saved = true;
		//$display_block = $LANG['save_payment_type_success'];
	} else {
		$saved = false;
		//$display_block =  $LANG['save_payment_type_failure'];
	}
	
	//header( 'refresh: 2; url=manage_payment_types.php' );


}


#edit payment type

else if (  $op === 'edit_payment_type' ) {

	/*$conn = mysql_connect("$db_host","$db_user","$db_password");
	mysql_select_db("$db_name",$conn); */

	if (isset($_POST['save_payment_type'])) {
		$sql = "UPDATE
				".TB_PREFIX."payment_types
			SET
				pt_description = :description,
				pt_enabled = :enabled
			WHERE
				pt_id = :id";

		if (dbQuery($sql, ':description', $_POST['pt_description'], ':enabled', $_POST['pt_enabled'], ':id', $_GET['id'])) {
			$saved = true;
			//$display_block = $LANG['save_payment_type_success'];
		} else {
			$saved = false;
			//$display_block =  $LANG['save_payment_type_failure'];
		}

		//header( 'refresh: 2; url=manage_payment_types.php' );
		//$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=payment_types&amp;view=manage' />";

	} 
}

//TODO: Make redirection with php..


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';


$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 
$smarty -> assign('saved',$saved); 

$smarty -> assign('pageActive', 'payment_type');
$smarty -> assign('active_tab', '#setting');
?>
