<?php
// -Gates 5/5/2008 added domain_id to parameters 
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert invoice_preference
if (  $op === 'insert_product_value' ) {

	$sql = "INSERT into
		".TB_PREFIX."products_values
	VALUES
		(
			NULL,
			:attribute_id,
			:value
		 )";

	if (dbQuery($sql,
	  ':attribute_id', $_POST['attribute_id'],
	  ':value', $_POST['value']
	  )) {
		$display_block = "Successfully saved";
	} else {
		$display_block = "Error occurred with saving";
	}

	//header( 'refresh: 2; url=manage_preferences.php' );
	$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=product_value&amp;view=manage' />";

}

#edit preference

if (  $op === 'edit_product_value' ) {

	if (isset($_POST['save_product_value'])) {
		$sql = "UPDATE
				".TB_PREFIX."products_values
			SET
				attribute_id = :attribute_id,
				value = :value
			WHERE
				id = :id";

		if (dbQuery($sql, 
		  ':attribute_id', $_POST['attribute_id'],
		  ':value', $_POST['value'],
		  ':id', $_GET['id'])) 
	    {
			$display_block = "Successfully saved";
		} else {
			$display_block = "Error occurred with saving";
		}

		//header( 'refresh: 2; url=manage_preferences.php' );
		$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=product_value&amp;view=manage' />";

		}
}


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$pageActive = "options";
$smarty->assign('pageActive', $pageActive);

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 
?>
