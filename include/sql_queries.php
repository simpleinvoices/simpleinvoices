<?php

$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


function getCustomer($id) {
	global $tb_prefix;
	$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $id";
	$result_print_customer = mysql_query($print_customer) or die(mysql_error());
	return mysql_fetch_array($result_print_customer);
}

function getBiller($id) {
	global $tb_prefix;
	$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE b_id = $id";
	$result_print_biller = mysql_query($print_biller) or die(mysql_error());
	return mysql_fetch_array($result_print_biller);
}

function getTaxRate($id) {
	global $tb_prefix;
	$print_tax_rate = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $id";
	$result_print_tax_rate = mysql_query($print_tax_rate) or die(mysql_error());
	return mysql_fetch_array($result_print_tax_rate);
}

function insertBiller() {
	global $tb_prefix;
	$sql = "INSERT into
			{$tb_prefix}biller
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

	return mysql_query($sql);
	
}

function updateBiller() {
	global $tb_prefix;
	$sql = "UPDATE
				{$tb_prefix}biller
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
	return mysql_query($sql);
}



//in this file are functions for all sql queries
?>