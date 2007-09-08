<?php

if(LOGGING) {
	//Logging connection to prevent mysql_insert_id problems. Need to be called before the second connect...
	$log = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $log );
}

$conn = mysql_connect( $db_host, $db_user, $db_password,true );
$db = mysql_select_db( $db_name, $conn );
$mysql = mysql_get_server_info();	//mysql_version

/**
 * Used for logging all queries
 */
function mysqlQuery($sqlQuery) {
	global $log;
	global $conn;
	$pattern = "/[^a-z]*select/i";
	$userid = 1;

	//error_log($sqlQuery);
	
	if($query = mysql_query($sqlQuery,$conn)) {
		
		//error_log("Insert_id: ".mysql_insert_id($conn));
		
		if(LOGGING && (preg_match($pattern,$sqlQuery) == 0)) {
			$sql = "INSERT INTO  `si_log` (`id`,`timestamp` ,  `userid` ,  `sqlquerie`, `last_id` ) VALUES (NULL,CURRENT_TIMESTAMP ,  '$userid',  '". addslashes (preg_replace('/\s\s+/', ' ', trim($sqlQuery)))."','".mysql_insert_id()."');";
			mysql_unbuffered_query($sql,$log);
		}
		return $query;
	}
	else {
		echo "Dude, what happened to your query?:<br><br> ".$sqlQuery."<br />".mysql_error();
	}
}


function getCustomer($id) {
	
	$print_customer = "SELECT * FROM ".TB_PREFIX."customers WHERE id = $id";
	$result_print_customer = mysqlQuery($print_customer) or die(mysql_error());
	return mysql_fetch_array($result_print_customer);
}

function getBiller($id) {
	
	global $LANG;
	$print_biller = "SELECT * FROM ".TB_PREFIX."biller WHERE id = $id";
	$result_print_biller = mysqlQuery($print_biller) or die(mysql_error());
	$biller = mysql_fetch_array($result_print_biller);
	$biller['wording_for_enabled'] = $biller['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $biller;
}

function getPreference($id) {
	
	global $LANG;
	$print_preferences = "SELECT * FROM ".TB_PREFIX."preferences WHERE pref_id = $id";
	$result_print_preferences  = mysqlQuery($print_preferences) or die(mysql_error());
	$preference = mysql_fetch_array($result_print_preferences);
	$preference['enabled'] = $preference['pref_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $preference;
}

function getSQLPatches() {
	
	$sql = "SELECT * FROM ".TB_PREFIX."sql_patchmanager ORDER BY sql_release";                  
	$query = mysqlQuery($sql) or die(mysql_error());

	$patches = null;
	
	for($i=0;$patch = mysql_fetch_array($query);$i++) {
		$patches[$i] = $patch;
	}
	return $patches;
}

function getPreferences() {
	
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences ORDER BY pref_description";
	$query  = mysqlQuery($sql) or die(mysql_error());
	
	$preferences = null;
	
	for($i=0;$preference = mysql_fetch_array($query);$i++) {
		
  		if ($preference['pref_enabled'] == 1) {
  			$preference['enabled'] = $LANG['enabled'];
  		} else {
  			$preference['enabled'] = $LANG['disabled'];
  		}

		$preferences[$i] = $preference;
	}
	
	return $preferences;
}

function getActiveTaxes() {
	
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_enabled != 0 ORDER BY tax_description";
	$query = mysqlQuery($sql) or die(mysql_error());
	
	$taxes = null;
	
	for($i=0;$tax = mysql_fetch_array($query);$i++) {
		if ($tax['tax_enabled'] == 1) {
			$tax['enabled'] = $LANG['enabled'];
		} else {
			$tax['enabled'] = $LANG['disabled'];
		}

		$taxes[$i] = $tax;
	}
	
	return $taxes;
}

function getActivePreferences() {
	
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences WHERE pref_enabled ORDER BY pref_description";
	$query  = mysqlQuery($sql) or die(mysql_error());
	
	$preferences = null;
	
	for($i=0;$preference = mysql_fetch_array($query);$i++) {
		$preferences[$i] = $preference;
	}
	
	return $preferences;
}

function getCustomFieldLabels() {
	global $LANG;
	
	
	$sql = "SELECT * FROM ".TB_PREFIX."custom_fields ORDER BY cf_custom_field";
	$result = mysqlQuery($sql) or die(mysql_error());
	
	for($i=0;$customField = mysql_fetch_array($result);$i++) {
		$customFields[$customField['cf_custom_field']] = $customField['cf_custom_label'];

		if($customFields[$customField['cf_custom_field']] == null) {
			//If not set, don't show...
			$customFields[$customField['cf_custom_field']] = $LANG["custom_field"].' '.($i%4+1);
		}
	}

	return $customFields;
}
 

function getBillers() {
	
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."biller ORDER BY name";
	$query  = mysqlQuery($sql) or die(mysql_error());
	
	$billers = null;
	
	for($i=0;$biller = mysql_fetch_array($query);$i++) {
		
  		if ($biller['enabled'] == 1) {
  			$biller['enabled'] = $LANG['enabled'];
  		} else {
  			$biller['enabled'] = $LANG['disabled'];
  		}
		$billers[$i] = $biller;
	}
	
	return $billers;
}

function getActiveBillers() {
	
	$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE enabled != 0 ORDER BY name";
	$query = mysqlQuery($sql) or die(mysql_error());
		
	$billers = null;
	
	for($i=0;$biller = mysql_fetch_array($query);$i++) {
		$billers[$i] = $biller;
	}
	
	return $billers;
}



function getTaxRate($id) {
	
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_id = $id";
	$query = mysqlQuery($sql) or die(mysql_error());
	
	$tax = mysql_fetch_array($query);
	$tax['enabled'] = $tax['tax_enabled'] == 1 ? $LANG['enabled']:$LANG['disabled'];
	
	return $tax;
}

function getPaymentType($id) {
	
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_id = $id";
	$query = mysqlQuery($sql) or die(mysql_error());
	$paymentType = mysql_fetch_array($query);
	$paymentType['enabled'] = $paymentType['pt_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	
	return $paymentType;
}

function getPayment($id) {
	global $config;
	$sql = "SELECT ".TB_PREFIX."account_payments.*, ".TB_PREFIX."customers.name AS customer, ".TB_PREFIX."biller.name AS biller FROM ".TB_PREFIX."account_payments, ".TB_PREFIX."invoices, ".TB_PREFIX."customers, ".TB_PREFIX."biller  WHERE ac_inv_id = ".TB_PREFIX."invoices.id AND ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id AND ".TB_PREFIX."invoices.biller_id = ".TB_PREFIX."biller.id AND ".TB_PREFIX."account_payments.id='$id'";

	$query = mysqlQuery($sql) or die(mysql_error());
	$payment = mysql_fetch_array($query);
	$payment['date'] = date( $config['date_format'], strtotime( $payment['ac_date'] ) );
	return $payment;
}

function getInvoicePayments($id) {
	$sql = "SELECT ".TB_PREFIX."account_payments.*, ".TB_PREFIX."customers.name as CNAME, ".TB_PREFIX."biller.name as BNAME from ".TB_PREFIX."account_payments, ".TB_PREFIX."invoices, ".TB_PREFIX."customers, ".TB_PREFIX."biller  where ac_inv_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id and ".TB_PREFIX."invoices.biller_id = ".TB_PREFIX."biller.id and ".TB_PREFIX."account_payments.ac_inv_id='$id' ORDER BY ".TB_PREFIX."account_payments.id DESC";
	return mysqlQuery($sql);
}


function getCustomerPayments($id) {
	$sql = "SELECT ".TB_PREFIX."account_payments.*, ".TB_PREFIX."customers.name as CNAME, ".TB_PREFIX."biller.name as BNAME from ".TB_PREFIX."account_payments, ".TB_PREFIX."invoices, ".TB_PREFIX."customers, ".TB_PREFIX."biller  where ac_inv_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id and ".TB_PREFIX."invoices.biller_id = ".TB_PREFIX."biller.id and ".TB_PREFIX."customers.id='$id' ORDER BY ".TB_PREFIX."account_payments.id DESC ";
	return mysqlQuery($sql);
}


function getPayments() {
	$sql = "SELECT ".TB_PREFIX."account_payments.*, ".TB_PREFIX."customers.name as CNAME, ".TB_PREFIX."biller.name as BNAME from ".TB_PREFIX."account_payments, ".TB_PREFIX."invoices, ".TB_PREFIX."customers, ".TB_PREFIX."biller  WHERE ac_inv_id = ".TB_PREFIX."invoices.id AND ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id and ".TB_PREFIX."invoices.biller_id = ".TB_PREFIX."biller.id ORDER BY ".TB_PREFIX."account_payments.id DESC";
	
	return mysqlQuery($sql);
}

function progressPayments($query) {
	$payments = null;

	for($i=0;$payment = mysql_fetch_array($query);$i++) {

		$sql = "SELECT pt_description FROM ".TB_PREFIX."payment_types WHERE pt_id = {$payment['ac_payment_type']}";
		$query2 = mysqlQuery($sql);

		$pt = mysql_fetch_array($query2);
		
		$payments[$i] = $payment;
		$payments[$i]['description'] = $pt['pt_description'];
		
	}
	
	return $payments;
}



function getPaymentTypes() {
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types ORDER BY pt_description";
	$query = mysqlQuery($sql);
	
	$paymentTypes = null;

	for ($i=0;$paymentType = mysql_fetch_array($query);$i++) {
		if ($paymentType['pt_enabled'] == 1) {
			$paymentType['pt_enabled'] = $LANG['enabled'];
		} else {
			$paymentType['pt_enabled'] = $LANG['disabled'];
		}
		$paymentTypes[$i]=$paymentType;
	}
	
	return $paymentTypes;
}

function getActivePaymentTypes() {
	
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_enabled != 0 ORDER BY pt_description";
	$query = mysqlQuery($sql) or die(mysql_error());
	
	$paymentTypes = null;

	for ($i=0;$paymentType = mysql_fetch_array($query);$i++) {
		if ($paymentType['pt_enabled'] == 1) {
			$paymentType['pt_enabled'] = $LANG['enabled'];
		} else {
			$paymentType['pt_enabled'] = $LANG['disabled'];
		}
		$paymentTypes[$i]=$paymentType;
	}
	
	return $paymentTypes;
}


function getProduct($id) {
	
	global $LANG;
	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = $id";
	$query = mysqlQuery($sql) or die(mysql_error());
	$product = mysql_fetch_array($query);
	$product['wording_for_enabled'] = $product['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $product;
}

/*function insertProduct($description,$unit_price,$enabled=1,$visible=1,$notes="",$custom_field1="",$custom_field2="",$custom_field3="",$custom_field4="") {
	$sql = "INSERT INTO ".TB_PREFIX."products (`description`,`unit_price`,`notes`,`enabled`,`visible`,`custom_field1`,`custom_field2`,`custom_field3`,`custom_field4`) 
	VALUES('$description','$unit_price','$notes',$enabled,$visible,'$custom_field1','$custom_field2','$custom_field3','$custom_field4');";
	
	return mysqlQuery($sql);
}*/


function insertProduct() {
	$sql = "INSERT into
			".TB_PREFIX."products
		VALUES
			(	
				'',
				'$_POST[description]',
				'$_POST[unit_price]',
				'$_POST[custom_field1]',
				'$_POST[custom_field2]',
				'$_POST[custom_field3]',
				'$_POST[custom_field4]',
				'$_POST[notes]',
				'$_POST[enabled]',
				'1'
			)";
	return mysqlQuery($sql);
}


function updateProduct() {
	
	$sql = "UPDATE ".TB_PREFIX."products
			SET
				description = '$_POST[description]',
				enabled = '$_POST[enabled]',
				notes = '$_POST[notes]',
				custom_field1 = '$_POST[custom_field1]',
				custom_field2 = '$_POST[custom_field2]',
				custom_field3 = '$_POST[custom_field3]',
				custom_field4 = '$_POST[custom_field4]',
				unit_price = '$_POST[unit_price]'
			WHERE
				id = '$_GET[id]'";

	return mysqlQuery($sql);
}
			

function getProducts() {
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE visible = 1 ORDER BY description";
	$query = mysqlQuery($sql) or die(mysql_error());
	
	$products = null;
	
	for($i=0;$product = mysql_fetch_array($query);$i++) {
		
		if ($product['enabled'] == 1) {
			$product['enabled'] = $LANG['enabled'];
		} else {
			$product['enabled'] = $LANG['disabled'];
		}

		$products[$i] = $product;
	}
	
	return $products;
}

function getActiveProducts() {
	
	
	
	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE enabled != 0 ORDER BY description";
	$query = mysqlQuery($sql) or die(mysql_error());
	
	$products = null;
	
	for($i=0;$product = mysql_fetch_array($query);$i++) {
		$products[$i] = $product;
	}
	
	return $products;
}


function getTaxes() {
	
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax ORDER BY tax_description";
	$query = mysqlQuery($sql) or die(mysql_error());
	
	$taxes = null;
	
	for($i=0;$tax = mysql_fetch_array($query);$i++) {
		if ($tax['tax_enabled'] == 1) {
			$tax['enabled'] = $LANG['enabled'];
		} else {
			$tax['enabled'] = $LANG['disabled'];
		}

		$taxes[$i] = $tax;
	}
	
	return $taxes;
}


function getDefaultCustomer() {
	
	$sql = "SELECT *,c.name AS name FROM ".TB_PREFIX."customers c, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'customer' AND c.id = s.value)";
	$query = mysqlQuery($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getDefaultPaymentType() {
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'payment_type' AND p.pt_id = s.value)";
	$query = mysqlQuery($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getDefaultPreference() {
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'preference' AND p.pref_id = s.value)";
	$query = mysqlQuery($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getDefaultBiller() {
	
	$sql = "SELECT *,b.name AS name FROM ".TB_PREFIX."biller b, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'biller' AND b.id = s.value )";
	$query = mysqlQuery($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}


function getDefaultTax() {
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax t, ".TB_PREFIX."system_defaults s WHERE (s.name = 'tax' AND t.tax_id = s.value)";
	$query = mysqlQuery($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function getDefaultDelete() {
	
	global $LANG;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'delete')";
	$query = mysqlQuery($sql) or die(mysql_error());
	$array = mysql_fetch_array($query);
	$delete = $array['value']==1?$LANG['enabled']:$LANG['disabled'];
	return $delete;
}

function getDefaultLogging() {
	
	global $LANG;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'logging')";
	$query = mysqlQuery($sql) or die(mysql_error());
	$array = mysql_fetch_array($query);
	$delete = $array['value']==1?$LANG['enabled']:$LANG['disabled'];
	return $delete;
}
function getInvoiceTotal($invoice_id) {
	global $LANG;
	
	
	$sql ="SELECT SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id = $invoice_id";
	$query = mysqlQuery($sql);
	$res = mysql_fetch_array($query);
	//echo "TOTAL".$res['total'];
	return $res['total'];
}

function getInvoice($id) {
	
	global $config;
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE id = $id";
	$query  = mysqlQuery($sql) or die(mysql_error());

	$invoice = mysql_fetch_array($query);
	$invoice['date'] = date( $config['date_format'], strtotime( $invoice['date'] ) );
	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['total'] = getInvoiceTotal($invoice['id']);
	$invoice['total_format'] = number_format($invoice['total'],2);
	$invoice['paid'] = calc_invoice_paid($invoice['id']);
	$invoice['paid_format'] = number_format($invoice['paid'],2);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];

	
	#invoice total tax
	$sql ="SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =$id";
	$query = mysqlQuery($sql) or die(mysql_error());
	$result = mysql_fetch_array($query);
	//$invoice['total'] = number_format($result['total'],2);
	$invoice['total_tax'] = number_format($result['total_tax'],2);
	
	return $invoice;
}


function getInvoiceItems($id) {
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id =$id";
	$query = mysqlQuery($sql);
	
	$invoiceItems = null;
	
	for($i=0;$invoiceItem = mysql_fetch_array($query);$i++) {
	
		$invoiceItem['quantity_formatted'] = number_format($invoiceItem['quantity'],2);
		$invoiceItem['unit_price'] = number_format($invoiceItem['unit_price'],2);
		$invoiceItem['tax_amount'] = number_format($invoiceItem['tax_amount'],2);
		$invoiceItem['gross_total'] = number_format($invoiceItem['gross_total'],2);
		$invoiceItem['total'] = number_format($invoiceItem['total'],2);
		
		$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = {$invoiceItem['product_id']}";
		$query2 = mysqlQuery($sql) or die(mysql_error());
		$invoiceItem['product'] = mysql_fetch_array($query2);	
		
		$invoiceItems[$i] = $invoiceItem;
	}
	
	return $invoiceItems;
}


function getSystemDefaults() {
	
	$print_defaults = "SELECT * FROM ".TB_PREFIX."system_defaults";
	$result_print_defaults = mysqlQuery($print_defaults) or die(mysql_error());
	
	$defaults = null;
	$default = null;
	
	
	while($default = mysql_fetch_array($result_print_defaults)) {
		$defaults["$default[name]"] = $default['value'];
	}

	return $defaults;
}

function updateDefault($name,$value) {
	
	$sql = "UPDATE ".TB_PREFIX."system_defaults SET `value` =  '$value' WHERE  `name` = '$name'"; 
	//echo $sql;
	if (mysqlQuery($sql)) {
		return true;
	}
	return false;
}

function getInvoiceType($id) {
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = $id";
	$query = mysqlQuery($sql) or die(mysql_error());
	return mysql_fetch_array($query);
}

function insertBiller() {
	
	$sql = "INSERT into
			".TB_PREFIX."biller
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


	return mysqlQuery($sql);
	/*
	if($query = mysqlQuery($sql)) {
		
		//error_log("iii:".mysql_insert_id());
		return $query;
	}
	else {
		return false;
	}*/
}

function updateBiller() {
	
	$sql = "UPDATE
				".TB_PREFIX."biller
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
				id = '$_GET[id]'";
	return mysqlQuery($sql);
}

function updateCustomer() {
	

	$sql = "
			UPDATE
				".TB_PREFIX."customers
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
				id = " . $_GET['id'];

	return mysqlQuery($sql);
}

function insertCustomer() {
	
	extract( $_POST );
	$sql = "INSERT INTO ".TB_PREFIX."customers VALUES ('','$attention', '$name','$street_address','$street_address2','$city','$state','$zip_code','$country','$phone', '$mobile_phone', '$fax', '$email', '$notes', '$custom_field1', '$custom_field2', '$custom_field3', '$custom_field4', '$enabled')";
	
	return mysqlQuery($sql);
	
}

function searchCustomers($search) {
	$sql = "SELECT * FROM  `si_customers` WHERE  `name` LIKE  '%$search%'";
	$query = mysqlQuery($sql);
	
	$customers = null;
	
	for($i=0;$customer = mysql_fetch_array($query);$i++) {
		$customers[$i] = $customer;
	}
	//echo $sql;
	
	//print_r($customers);
	return $customers;
}	
		

function getInvoices(&$query) {
	global $config;
	$invoice = null;

	if($invoice =  mysql_fetch_array($query)) {

		$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['date'] ) );
		$invoice['date'] = date( $config['date_format'], strtotime( $invoice['date'] ) );
			
		#invoice total total - start
		$invoice['total'] = getInvoiceTotal($invoice['id']);
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
	
	$invoices = null;
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE customer_id =$id  ORDER BY id DESC";
	$query = mysqlQuery($sql) or die(mysql_error());
	
	for($i = 0;$invoice = getInvoices($query);$i++) {
		$invoices[$i] = $invoice;
	}
	
	return $invoices;

}

function getCustomers() {
		
	global $LANG;
	
	$customer = null;
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY name";
	$result = mysqlQuery($sql) or die(mysql_error());

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

function getActiveCustomers() {
		
	global $LANG;
	
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled != 0 ORDER BY name";
	$result = mysqlQuery($sql) or die(mysql_error());

	$customers = null;

	for($i=0;$customer = mysql_fetch_array($result);$i++) {
		$customers[$i] = $customer;
	}
	
	return $customers;
}

function insertInvoice($type) {
	
	
	$sql = "INSERT 
			INTO
		".TB_PREFIX."invoices (
			id, 
			biller_id, 
			customer_id, 
			type_id,
			preference_id, 
			date, 
			note,
			custom_field1,
			custom_field2,
			custom_field3,
			custom_field4
		)
		VALUES
		(
			'',
			'$_POST[biller_id]',
			'$_POST[customer_id]',
			'$type',
			'$_POST[preference_id]',
			'$_POST[date]',
			'$_POST[note]',
			'$_POST[customField1]',
			'$_POST[customField2]',
			'$_POST[customField3]',
			'$_POST[customField4]'
			)";
	//echo $sql;
	return mysqlQuery($sql);
}

function updateInvoice($invoice_id) {
	
		$sql = "UPDATE
			".TB_PREFIX."invoices
		SET
			biller_id = '$_POST[biller_id]',
			customer_id = '$_POST[customer_id]',
			preference_id = '$_POST[preference_id]',
			date = '$_POST[date]',
			note = '$_POST[note]',
			custom_field1 = '$_POST[customField1]',
			custom_field2 = '$_POST[customField2]',
			custom_field3 = '$_POST[customField3]',
			custom_field4 = '$_POST[customField4]'
		WHERE
			id = $invoice_id";
			
	return mysqlQuery($sql);
}

function insertInvoiceItem($invoice_id,$quantity,$product_id,$tax_id,$description="") {
	
	$tax = getTaxRate($tax_id);
	$product = getProduct($product_id);
	//print_r($product);
	$actual_tax = $tax['tax_percentage']  / 100 ;
	$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
	$tax_amount = $total_invoice_item_tax * $quantity;
	$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;	
	$total = $total_invoice_item * $quantity;
	$gross_total = $product['unit_price']  * $quantity;
	
	$sql = "INSERT INTO ".TB_PREFIX."invoice_items (`invoice_id`,`quantity`,`product_id`,`unit_price`,`tax_id`,`tax`,`tax_amount`,`gross_total`,`description`,`total`) VALUES ($invoice_id,$quantity,$product_id,$product[unit_price],'$tax[tax_id]',$tax[tax_percentage],$tax_amount,$gross_total,'$description',$total)";

	//echo $sql;
	return mysqlQuery($sql);

}

function updateInvoiceItem($id,$quantity,$product_id,$tax_id,$description) {

	
	
	$product = getProduct($product_id);
	$tax = getTaxRate($tax_id);
	
	$total_invoice_item_tax = $product['unit_price'] * $tax['tax_percentage'] / 100;	//:100?
	$tax_amount = $total_invoice_item_tax * $quantity;
	$total_invoice_item = $total_invoice_item_tax + $product['unit_price'];
	$total = $total_invoice_item * $quantity;
	$gross_total = $product['unit_price'] * $quantity;
	
	
	
	$sql = "UPDATE ".TB_PREFIX."invoice_items 
	SET `quantity` =  '$quantity',
	`product_id` = '$product_id',
	`unit_price` = '$product[unit_price]',
	`tax_id` = '$tax_id',
	`tax` = '$tax[tax_percentage]',
	`tax_amount` = '$tax_amount',
	`gross_total` = '$gross_total',
	`description` = '$description',
	`total` = '$total'			
	WHERE  `id` = '$id'";
	
	//echo $sql;
		
	return mysqlQuery($sql);
}

function getMenuStructure() {
	global $LANG;
	$sql = "SELECT * FROM  `si_menu` WHERE enabled = 1 ORDER BY parentid,  `order`";
	$query = mysqlQuery($sql) or die(mysql_error());
	$menu = null;
	
	while($res = mysql_fetch_array($query)) {
		//error_log($res['name']);
		$menu[$res['parentid']][$res['id']]["name"] = eval('return "'.$res['name'].'";');
		$menu[$res['parentid']][$res['id']]["link"] = $res['link'];
		$menu[$res['parentid']][$res['id']]["id"] = $res['id'];
	}
	
	echo <<<EOD
	<div id="Header">
		<div id="Tabs">
			<ul id="navmenu">
EOD;

	printEntries($menu,0,1);

echo <<<EOD
		</div id="Tabs">
	</div id="Header">
EOD;

}


function printEntries($menu,$id,$depth) {
	
	foreach($menu[$id] as $tempentrie) {
		for($i=0;$i<$depth;$i++) {
			//echo "&nbsp;&nbsp;&nbsp;";
		}
		echo <<<EOD
		<li><a href="$tempentrie[link]">$tempentrie[name]</a>
EOD;
		
		if(isset($menu[$tempentrie["id"]])) {
			echo "<ul>";
			printEntries($menu,$tempentrie["id"],$depth+1);
			echo "</ul>";
		}
		echo "</li>\n";
	}
}

function searchBillerAndCustomerInvoice($biller,$customer) {
	$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date, i.type_id AS type_id,t.inv_ty_description as type
	FROM si_biller b, si_invoices i, si_customers c, si_invoice_type t
	WHERE b.name LIKE  '%$biller%'
	AND c.name LIKE  '%$customer%' 
	AND i.biller_id = b.id 
	AND i.customer_id = c.id
	AND i.type_id = t.inv_ty_id";
	return mysqlQuery($sql);
}

function searchInvoiceByDate($startdate,$enddate) {
	$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date,i.type_id AS type_id, t.inv_ty_description as type
	FROM si_biller b, si_invoices i, si_customers c, si_invoice_type t
	WHERE i.date >= '$startdate' 
	AND i.date <= '$enddate'
	AND i.biller_id = b.id 
	AND i.customer_id = c.id
	AND i.type_id = t.inv_ty_id";
	return mysqlQuery($sql);
}

function delete($module,$idField,$id) {
	$sql = "DELETE FROM ".TB_PREFIX."$module WHERE $idField = $id";
	return mysqlQuery($sql);
}

function maxInvoice() {

	global $LANG;	
	$sql = "SELECT max(id) as maxId FROM ".TB_PREFIX."invoices";

	$resultSql = mysqlQuery($sql);
	return mysql_fetch_array($resultSql);
	
//while ($Array_max = mysql_fetch_array($result_max) ) {
//$max_invoice_id = $Array_max['max_inv_id'];
};



//in this file are functions for all sql queries

?>
