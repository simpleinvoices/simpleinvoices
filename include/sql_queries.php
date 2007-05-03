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
	$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE id = $id";
	$result_print_biller = mysql_query($print_biller) or die(mysql_error());
	return mysql_fetch_array($result_print_biller);
}

function getPreferences($id) {
	global $tb_prefix;
	$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $id";
	$result_print_preferences  = mysql_query($print_preferences) or die(mysql_error());
	return mysql_fetch_array($result_print_preferences);
}

function getTaxRate($id) {
	global $tb_prefix;
	$print_tax_rate = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $id";
	$result_print_tax_rate = mysql_query($print_tax_rate) or die(mysql_error());
	return mysql_fetch_array($result_print_tax_rate);
}

function getInvoice($id) {
	global $tb_prefix;
	$print_master_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE inv_id = $id";
	$result_print_master_invoice_id  = mysql_query($print_master_invoice_id) or die(mysql_error());

	$invoice = mysql_fetch_array($result_print_master_invoice_id);
	$invoice['date_field'] = date( $config['date_format'], strtotime( $invoice['inv_date'] ) );
	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['inv_date'] ) );
	
	return $invoice;
}

function getDefaults() {
	global $tb_prefix;
	$print_defaults = "SELECT * FROM {$tb_prefix}defaults WHERE def_id = 1";
	$result_print_defaults = mysql_query($print_defaults) or die(mysql_error());

	return mysql_fetch_array($result_print_defaults);
}

function getSystemDefaults() {
	global $tb_prefix;
	$print_defaults = "SELECT * FROM {$tb_prefix}systemdefaults";
	$result_print_defaults = mysql_query($print_defaults) or die(mysql_error());
	
	$defaults = null;
	$default = null;
	
	while($default = mysql_fetch_array($result_print_defaults)) {
		$defaults["$default[name]"] = $default['value'];
	}

	return $defaults;
}

function updateDefault($name,$value) {
	global $tb_prefix;
	$sql = "UPDATE {$tb_prefix}systemdefaults SET `value` =  '$value' WHERE  `name` = '$name'"; 
	//echo $sql;
	if (mysql_query($sql)) {
		return true;
	}
	return false;
}

function getInvoiceType($id) {
	global $tb_prefix;
	$sql_invoice_type = "SELECT inv_ty_description FROM {$tb_prefix}invoice_type WHERE inv_ty_id = $id";
	$result_invoice_type = mysql_query($sql_invoice_type) or die(mysql_error());
	return mysql_fetch_array($result_invoice_type);
}

function insertBiller() {
	global $tb_prefix;
	$sql = "INSERT into
			{$tb_prefix}biller
		VALUES
			(
				'',
				'$_POST[name]',
				'$_POST[street_address]',
				'$_POST[street_address2]',
				'$_POST[city]',
				'$_POST[state]',
				'$_POST[zip_code]',
				'$_POST[country]',
				'$_POST[phone]',
				'$_POST[mobile_phone]',
				'$_POST[fax]',
				'$_POST[email]',
				'$_POST[logo]',
				'$_POST[footer]',
				'$_POST[notes]',
				'$_POST[custom_field1]',
				'$_POST[custom_field2]',
				'$_POST[custom_field3]',
				'$_POST[custom_field4]',
				'$_POST[enabled]'
			 )";

	return mysql_query($sql);
	
}

function updateBiller() {
	global $tb_prefix;
	$sql = "UPDATE
				{$tb_prefix}biller
			SET
				name = '$_POST[name]',
				street_address = '$_POST[street_address]',
				street_address2 = '$_POST[street_address2]',
				city = '$_POST[city]',
				state = '$_POST[state]',
				zip_code = '$_POST[zip_code]',
				country = '$_POST[country]',
				phone = '$_POST[phone]',
				mobile_phone = '$_POST[mobile_phone]',
				fax = '$_POST[fax]',
				email = '$_POST[email]',
				logo = '$_POST[logo]',
				footer = '$_POST[footer]',
				notes = '$_POST[notes]',
				custom_field1 = '$_POST[custom_field1]',
				custom_field2 = '$_POST[custom_field2]',
				custom_field3 = '$_POST[custom_field3]',
				custom_field4 = '$_POST[custom_field4]',
				enabled = '$_POST[enabled]'
			WHERE
				id = '$_GET[submit]'";
	return mysql_query($sql);
}

function saveCustomer() {
	global $tb_prefix;

	$sql = "
			UPDATE
				{$tb_prefix}customers
			SET
				c_name = '$_POST[c_name]',
				c_attention = '$_POST[c_attention]',
				c_street_address = '$_POST[c_street_address]',
				c_street_address2 = '$_POST[c_street_address2]',
				c_city = '$_POST[c_city]',
				c_state = '$_POST[c_state]',
				c_zip_code = '$_POST[c_zip_code]',
				c_country = '$_POST[c_country]',
				c_phone = '$_POST[c_phone]',
				c_mobile_phone = '$_POST[c_mobile_phone]',
				c_fax = '$_POST[c_fax]',
				c_email = '$_POST[c_email]',
				c_notes = '$_POST[c_notes]',
				c_custom_field1 = '$_POST[c_custom_field1]',
				c_custom_field2 = '$_POST[c_custom_field2]',
				c_custom_field3 = '$_POST[c_custom_field3]',
				c_custom_field4 = '$_POST[c_custom_field4]',
				c_enabled = '$_POST[c_enabled]'
			WHERE
				c_id = " . $_GET['submit'];

	return mysql_query($sql);
}

//in this file are functions for all sql queries
?>
