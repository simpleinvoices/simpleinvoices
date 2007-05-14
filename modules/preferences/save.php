<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert invoice_preference
if (  $op === 'insert_preference' ) {

$sql = "INSERT into
		{$tb_prefix}preferences
	VALUES
		(
			'',
			'$_POST[p_description]',
			'$_POST[p_currency_sign]',
			'$_POST[p_inv_heading]',
			'$_POST[p_inv_wording]',
			'$_POST[p_inv_detail_heading]',
			'$_POST[p_inv_detail_line]',
			'$_POST[p_inv_payment_method]',
			'$_POST[p_inv_payment_line1_name]',
			'$_POST[p_inv_payment_line1_value]',
			'$_POST[p_inv_payment_line2_name]',
			'$_POST[p_inv_payment_line2_value]',
			'$_POST[pref_enabled]'
		 )";

if (mysqlQuery($sql, $conn)) {
	$display_block = $LANG['save_preference_success'];
} else {
	$display_block =  $LANG['save_preference_failure'];
}

//header( 'refresh: 2; url=manage_preferences.php' );
$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=preferences&view=manage>";

}

#edit preference

else if (  $op === 'edit_preference' ) {

	if (isset($_POST['save_preference'])) {
		$sql = "UPDATE
				{$tb_prefix}preferences
			SET
				pref_description = '$_POST[pref_description]',
				pref_currency_sign = '$_POST[pref_currency_sign]',
				pref_inv_heading = '$_POST[pref_inv_heading]',
				pref_inv_wording = '$_POST[pref_inv_wording]',
				pref_inv_detail_heading = '$_POST[pref_inv_detail_heading]',
				pref_inv_detail_line = '$_POST[pref_inv_detail_line]',
				pref_inv_payment_method = '$_POST[pref_inv_payment_method]',
				pref_inv_payment_line1_name = '$_POST[pref_inv_payment_line1_name]',
				pref_inv_payment_line1_value = '$_POST[pref_inv_payment_line1_value]',
				pref_inv_payment_line2_name = '$_POST[pref_inv_payment_line2_name]',
				pref_inv_payment_line2_value = '$_POST[pref_inv_payment_line2_value]',
				pref_enabled = '$_POST[pref_enabled]'
			WHERE
				pref_id = '$_GET[submit]'";

		if (mysqlQuery($sql, $conn)) {
			$display_block = $LANG['save_preference_success'];
		} else {
			$display_block = $LANG['save_preference_failure'];
		}

		//header( 'refresh: 2; url=manage_preferences.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=preferences&view=manage>";

		}

	else if ($_POST[action] == "Cancel") {

		//header( 'refresh: 0; url=manage_preferences.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=preferences&view=manage>";
	}
}


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 
?>
