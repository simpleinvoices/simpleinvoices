<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert customer

if ($op === "insert_customer") {

	//TODO: What's happening? Which vars are extracted? Not secure...
	extract( $_POST );

	$sql ='INSERT INTO '.$tb_prefix.'customers VALUES ("","' . $c_attention . '", "' . $c_name . '", "' . $c_street_address . '", "' . $c_street_address2 . '",  "' . $c_city . '", "' . $c_state . '", "' . $c_zip_code . '", "' . $c_country . '", "' . $c_phone . '", "' . $c_mobile_phone . '", "' . $c_fax . '", "' . $c_email . '", "' . $c_notes . '", "' . $c_custom_field1 . '", "' . $c_custom_field2 . '", "' . $c_custom_field3 . '", "' . $c_custom_field4 . '", "' . $c_enabled . '")';

	if (mysql_query($sql)) {
		$display_block = $LANG['save_customer_success'];
	} else {
		$display_block = $LANG['save_customer_failure'];
	}
	//TODO: Refresh over php?
	//header( 'refresh: 2; url=manage_customers.php' );
	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=customers&view=manage>";
}

#edit customer
else if ( $op === 'edit_customer' ) {

	if (isset($_POST['save_customer'])) {

		if (saveCustomer()) {
			$display_block = $LANG['save_customer_success'];
		} else {
			$display_block =  $LANG['save_customer_failure'];
		}

		//header( 'refresh: 2; url=manage_customers.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=customers&view=manage>";

	}

	else if (isset($_POST['cancel'])) {
		//header( 'refresh: 0; url=manage_customers.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=customers&view=manage>";
	}
}


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';

echo <<<EOD
$refresh_total
</head>

<body>

<br>
<br>
$display_block
<br><br>
$display_block_items

EOD;
?>
