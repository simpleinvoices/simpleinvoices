<?php
// -Gates 5/5/2008 added domain_id to parameters
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = $_POST['op'] ?? null;


#insert invoice_preference
if (  $op === 'insert_product_value' ) {

	$domain_id = domain_id::get();
	$sql = "INSERT into
		".TB_PREFIX."products_values
		(domain_id, attribute_id, value, enabled)
	VALUES
		(
			:domain_id,
			:attribute_id,
            :value,
            :enabled
		 )";

	if (dbQuery($sql,
	  ':domain_id', $domain_id,
	  ':attribute_id', $_POST['attribute_id'],
	  ':value', $_POST['value'],
	  ':enabled', $_POST['enabled']
	  )) {
		$saved = true;
		$display_block = "Successfully saved";
	} else {
		$saved = false;
		$display_block = "Error occurred with saving";
	}

	//header( 'refresh: 2; url=manage_preferences.php' );
	$refresh_total = "<meta http-equiv='refresh' content='20;url=index.php?module=product_value&amp;view=manage' />";

}

#edit preference

if (  $op === 'edit_product_value' ) {

	if (isset($_POST['save_product_value'])) {
		$domain_id = domain_id::get();
		$sql = "UPDATE
				".TB_PREFIX."products_values
			SET
				attribute_id = :attribute_id,
				value = :value,
				enabled = :enabled
			WHERE
				id = :id
			AND domain_id = :domain_id";

		if (dbQuery($sql,
		  ':attribute_id', $_POST['attribute_id'],
		  ':value', $_POST['value'],
		  ':enabled', $_POST['enabled'],
		  ':id', $_GET['id'],
		  ':domain_id', $domain_id))
	    {
			$saved = true;
			$display_block = "Successfully saved";
		} else {
			$saved = false;
			$display_block = "Error occurred with saving";
		}

		//header( 'refresh: 2; url=manage_preferences.php' );
		$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=product_value&amp;view=manage' />";

		}
}


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$pageActive = "product_value_manage";
$bladeView->assign('pageActive', $pageActive);
$bladeView -> assign('active_tab', '#product');

$bladeView -> assign('saved', isset($saved) ? $saved : null);
$bladeView -> assign('display_block',$display_block);
$bladeView -> assign('refresh_total',$refresh_total);
?>
