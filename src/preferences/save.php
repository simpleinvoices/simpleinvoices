<?php
include('./config/config.php');

$conn = mysql_connect( $db_host, $db_user, $db_password);
mysql_select_db( $db_name, $conn);

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert invoice_preference
if (  $op === 'insert_preference' ) {

$sql = "INSERT into
		si_preferences
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

if (mysql_query($sql, $conn)) {
$display_block =  "Invoice preference successfully added,<br> you will be redirected to Manage Preferences page";
} else {
	$display_block =  'Something went wrong, please try adding the invoice preference again';
}

//header( 'refresh: 2; url=manage_preferences.php' );
$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=preferences&view=manage>";

}

#edit preference

else if (  $op === 'edit_preference' ) {

	if (isset($_POST['save_preference'])) {
		$sql = "UPDATE
				si_preferences
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

		if (mysql_query($sql, $conn)) {
			$display_block =  "Invoice Preference successfully edited, <br> you will be redirected back to Manage Invoice Preferences";
		} else {
			$display_block =  "Something went wrong, please try editing the invoice preference again";
		}

		//header( 'refresh: 2; url=manage_preferences.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=preferences&view=manage>";

		}

	else if ($_POST[action] == "Cancel") {

		//header( 'refresh: 0; url=manage_preferences.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=preferences&view=manage>";
	}
}


?>

<html>
<head>
<head>
<?php

include('./include/include_main.php');

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';
echo <<<EOD
{$refresh_total}
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css">
</head>

<body>

EOD;
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD
<br>
<br>
{$display_block}
<br><br>
{$display_block_items}

EOD;
?>
</body>
</html>
