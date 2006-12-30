<?php
include('./config/config.php');

$conn = mysql_connect( $db_host, $db_user, $db_password);
mysql_select_db( $db_name, $conn);

# Deal with op and add some basic sanity checking

$action = !empty( $_POST['action'] ) ? addslashes( $_POST['action'] ) : NULL;

#insert biller
 	
if ( $action === 'insert_biller') {
	
 	$sql = "INSERT into
			si_biller
		VALUES
			(
				'',
				'$_POST[b_name]',
				'$_POST[b_street_address]',
				'$_POST[b_street_address2]',
				'$_POST[b_city]',
				'$_POST[b_state]',
				'$_POST[b_zip_code]',
				'$_POST[b_country]',
				'$_POST[b_phone]',
				'$_POST[b_mobile_phone]',
				'$_POST[b_fax]',
				'$_POST[b_email]',
				'$_POST[b_co_logo]',
				'$_POST[b_co_footer]',
				'$_POST[b_notes]',
				'$_POST[b_custom_field1]',
				'$_POST[b_custom_field2]',
				'$_POST[b_custom_field3]',
				'$_POST[b_custom_field4]',
				'$_POST[b_enabled]'
			 )";
 	
 	if (mysql_query($sql, $conn)) {
 		$display_block =  "Biller successfully added, <br> you will be redirected to the Manage Billers page";
 	} else {
 		$display_block =  "Something went wrong, please try adding the biller again<br>$sql";
 	}
 	
 	header( 'refresh: 2; url=index.php?module=billers&view=manage' );
 	
}

#edit biller

else if (  $action === 'edit_biller' ) {

	if (isset($_POST['save_biller'])) {
		$sql = "UPDATE
				si_biller
			SET
				b_name = '$_POST[b_name]',
				b_street_address = '$_POST[b_street_address]',
				b_street_address2 = '$_POST[b_street_address2]',
				b_city = '$_POST[b_city]',b_state = '$_POST[b_state]',
				b_zip_code = '$_POST[b_zip_code]',
				b_country = '$_POST[b_country]',
				b_phone = '$_POST[b_phone]',
				b_mobile_phone = '$_POST[b_mobile_phone]',
				b_fax = '$_POST[b_fax]',
				b_email = '$_POST[b_email]',
				b_co_logo = '$_POST[b_co_logo]',
				b_co_footer = '$_POST[b_co_footer]',
				b_notes = '$_POST[b_notes]',
				b_custom_field1 = '$_POST[b_custom_field1]',
				b_custom_field2 = '$_POST[b_custom_field2]',
				b_custom_field3 = '$_POST[b_custom_field3]',
				b_custom_field4 = '$_POST[b_custom_field4]',
				b_enabled = '$_POST[b_enabled]'
			WHERE
				b_id = '$_GET[submit]'";
		if (mysql_query($sql, $conn)) {
			$display_block =  "Biller successfully edited, <br> you will be redirected back to the Manage Billers";
		} else {
			$display_block =  "Something went wrong, please try editing the product again";
		}

		header( 'refresh: 2; url=index.php?module=billers&view=manage' );

		}

	else if (isset($_POST['cancel'])) {

		header( 'refresh: 0; url=index.php?module=billers&view=manage' );
	}


	header( 'refresh: 2; url=system_default_details.php' );
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

