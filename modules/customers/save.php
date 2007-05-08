<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert customer

if ($op === "insert_customer") {

	//TODO: What's happening? Which vars are extracted? Not secure...
	extract( $_POST );

	$sql ='INSERT INTO '.$tb_prefix.'customers VALUES ("","' . $attention . '", "' . $name . '", "' . $street_address . '", "' . $street_address2 . '",  "' . $city . '", "' . $state . '", "' . $zip_code . '", "' . $country . '", "' . $phone . '", "' . $mobile_phone . '", "' . $fax . '", "' . $email . '", "' . $notes . '", "' . $custom_field1 . '", "' . $custom_field2 . '", "' . $custom_field3 . '", "' . $custom_field4 . '", "' . $enabled . '")';

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

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 
?>
