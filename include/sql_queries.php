<?php

$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


function getCustomer($id) {
	global $tb_prefix;
	$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE id = $id";
	$result_print_customer = mysql_query($print_customer) or die(mysql_error());
	return mysql_fetch_array($result_print_customer);
}

function getBiller($id) {
	global $tb_prefix;
	$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE id = $id";
	$result_print_biller = mysql_query($print_biller) or die(mysql_error());
	return mysql_fetch_array($result_print_biller);
}

function getPreference($id) {
	global $tb_prefix;
	global $LANG;
	$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $id";
	$result_print_preferences  = mysql_query($print_preferences) or die(mysql_error());
	$preference = mysql_fetch_array($result_print_preferences);
	$preference['wording_for_enabled'] = $preference['pref_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $preference;
}

function getPreferences() {
	global $tb_prefix;
	global $LANG;
	
	$sql = "SELECT * FROM {$tb_prefix}preferences ORDER BY pref_description";
	$query  = mysql_query($sql) or die(mysql_error());
	
	$preferences = null;
	
	for($i=0;$preference = mysql_fetch_array($query);$i++) {
		$preferences[$i] = $preference;
		
  		if ($preference['pref_enabled'] == 1) {
  			$preference['pref_enabled'] = $LANG['enabled'];
  		} else {
  			$preference['pref_enabled'] = $LANG['disabled'];
  		}
	}
	
	return $preferences;
}

function getTaxRate($id) {
	global $tb_prefix;
	$print_tax_rate = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $id";
	$result_print_tax_rate = mysql_query($print_tax_rate) or die(mysql_error());
	return mysql_fetch_array($result_print_tax_rate);
}

function getPaymentType($id) {
	global $tb_prefix;
	global $LANG;
	
	$sql = "SELECT * FROM {$tb_prefix}payment_types WHERE pt_id = $id";
	$query = mysql_query($sql) or die(mysql_error());
	$paymentType = mysql_fetch_array($query);
	$paymentType['pt_enabled'] = $paymentType['pt_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	
	return $paymentType;
}

function getProducts() {
	
	global $tb_prefix;
	global $LANG;
	
	$sql = "SELECT * FROM {$tb_prefix}products ORDER BY description";
	$query = mysql_query($sql) or die(mysql_error());
	
	$products = null;
	
	for($i=0;$product = mysql_fetch_array($query);$i++) {
		$products[$i] = $product;
	}
	
	return $products;
}

function getDefaultCustomer() {
	global $tb_prefix;
	$sql = "SELECT *,c.name AS name FROM {$tb_prefix}customers c, {$tb_prefix}systemdefaults s WHERE ( s.name = 'customer' AND c.id = s.value)";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getDefaultPaymentType() {
	global $tb_prefix;
	$sql = "SELECT * FROM {$tb_prefix}payment_types p, {$tb_prefix}systemdefaults s WHERE ( s.name = 'payment_type' AND p.pt_id = s.value)";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getDefaultInvoice() {
	global $tb_prefix;
	$sql = "SELECT * FROM {$tb_prefix}preferences p, {$tb_prefix}systemdefaults s WHERE ( s.name = 'invoice' AND p.pref_id = s.value)";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getDefaultBiller() {
	global $tb_prefix;
	$sql = "SELECT *,b.name AS name FROM {$tb_prefix}biller b, {$tb_prefix}systemdefaults s WHERE ( s.name = 'biller' AND b.id = s.value )";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}


function getDefaultTax() {
	global $tb_prefix;
	$sql = "SELECT * FROM {$tb_prefix}tax t, {$tb_prefix}systemdefaults s WHERE (s.name = 'tax' AND t.tax_id = s.value)";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getInvoice($id) {
	global $tb_prefix;
	global $config;
	$print_master_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE id = $id";
	$result_print_master_invoice_id  = mysql_query($print_master_invoice_id) or die(mysql_error());

	$invoice = mysql_fetch_array($result_print_master_invoice_id);
	$invoice['date_field'] = date( $config['date_format'], strtotime( $invoice['date'] ) );
	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	
	return $invoice;
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
				name = '$_POST[name]',
				attention = '$_POST[attention]',
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
				notes = '$_POST[notes]',
				custom_field1 = '$_POST[custom_field1]',
				custom_field2 = '$_POST[custom_field2]',
				custom_field3 = '$_POST[custom_field3]',
				custom_field4 = '$_POST[custom_field4]',
				enabled = '$_POST[enabled]'
			WHERE
				id = " . $_GET['submit'];

	return mysql_query($sql);
}

function getInvoices(&$query) {
	global $config;
	$invoice = null;

	if($invoice =  mysql_fetch_array($query)) {

		$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['date'] ) );
		$invoice['date'] = date( $config['date_format'], strtotime( $invoice['date'] ) );
			
		#invoice total total - start
		$invoice['total'] = calc_invoice_total($invoice['id']);
		$invoice['total_format'] = number_format($invoice['total'],2);
		#invoice total total - end
		
		#amount paid calc - start
		$invoice['paid'] = calc_invoice_paid($invoice['id']);
		$invoice['paid_format'] = number_format($invoice['paid'],2);
		#amount paid calc - end
		
		#amount owing calc - start
		$invoice['owing'] = $invoice['total'] - $invoice['paid'];
		$invoice['owing_format'] = number_format($invoice['total'] - $invoice['paid'],2);
		#amount owing calc - end
	}
	return $invoice;
}

function getCustomerInvoices($id) {
	global $tb_prefix;
	$invoices = null;
	
	$sql = "SELECT * FROM {$tb_prefix}invoices WHERE customer_id =$id  ORDER BY id DESC";
	$query = mysql_query($sql) or die(mysql_error());
	
	for($i = 0;$invoice = getInvoices($query);$i++) {
		$invoices[$i] = $invoice;
	}
	
	return $invoices;

}

function getCustomers() {
		
	global $LANG;
	global $tb_prefix;
	$customer = null;
	
	$sql = "SELECT * FROM {$tb_prefix}customers ORDER BY name";
	$result = mysql_query($sql) or die(mysql_error());

	$customers = null;

	for($i=0;$customer = mysql_fetch_array($result);$i++) {
		if ($customer['enabled'] == 1) {
			$customer['enabled'] = $LANG['enabled'];
		} else {
			$customer['enabled'] = $LANG['disabled'];
		}

		#invoice total calc - start
		$customer['total'] = calc_customer_total($customer['id']);
		#invoice total calc - end

		#amount paid calc - start
		$customer['paid'] = calc_customer_paid($customer['id']);
		#amount paid calc - end

		#amount owing calc - start
		$customer['owing'] = $customer['total'] - $customer['paid'];
		
		#amount owing calc - end
		$customers[$i] = $customer;

	}
	
	return $customers;
}
		
//in this file are functions for all sql queries
?>
