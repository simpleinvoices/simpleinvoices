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
			$sql = "INSERT INTO  `".TB_PREFIX."log` (`id`,`timestamp` ,  `userid` ,  `sqlquerie`, `last_id` ) VALUES (NULL,CURRENT_TIMESTAMP ,  '$userid',  '". addslashes (preg_replace('/\s\s+/', ' ', trim($sqlQuery)))."','".mysql_insert_id()."');";
			mysql_unbuffered_query($sql,$log);
		}
		return $query;
	}
	else {
		echo "Dude, what happened to your query?:<br><br> ".$sqlQuery."<br />".mysql_error();
	}
}

function sql2array($strSql) {
	$sqlInArray = null;

	$result_strSql = mysqlQuery($strSql);

	for($i=0;$sqlInRow = mysql_fetch_array($result_strSql);$i++) {

		$sqlInArray[$i] = $sqlInRow;
	}
	return $sqlInArray;
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
	return sql2array($sql);
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
	return sql2array($sql);
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
	return sql2array($sql);
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
	$sql = "SELECT ap.*, c.name AS customer, b.name AS biller FROM ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b  WHERE ap.ac_inv_id = iv.id AND iv.customer_id = c.id AND iv.biller_id = b.id AND ap.id=$id";

	$query = mysqlQuery($sql) or die(mysql_error());
	$payment = mysql_fetch_array($query);
	$payment['date'] = date( $config['date_format'], strtotime( $payment['ac_date'] ) );
	return $payment;
}

function getInvoicePayments($id) {
	$sql = "SELECT ap.*, c.name AS CNAME, b.name AS BNAME, pt.pt_description AS description FROM ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b, ".TB_PREFIX."payment_types pt WHERE ap.ac_inv_id = iv.id and iv.customer_id = c.id and iv.biller_id = b.id AND ap.ac_payment_type = pt.pt_id AND ap.ac_inv_id = $id ORDER BY ap.id DESC";
	return sql2array($sql);
}

function getCustomerPayments($id) {
	$sql = "SELECT ap.*, c.name AS CNAME, b.name AS BNAME, pt.pt_description AS description FROM ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b, ".TB_PREFIX."payment_types pt WHERE ap.ac_inv_id = iv.id and iv.customer_id = c.id and iv.biller_id = b.id AND ap.ac_payment_type = pt.pt_id AND c.id = $id ORDER BY ap.id DESC";
	return sql2array($sql);
}

function getPayments() {
	$sql = "SELECT ap.*, c.name AS CNAME, b.name AS BNAME, pt.pt_description AS description FROM ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b, ".TB_PREFIX."payment_types pt WHERE ap.ac_inv_id = iv.id AND iv.customer_id = c.id and iv.biller_id = b.id AND ap.ac_payment_type = pt.pt_id ORDER BY ap.id DESC";
	return sql2array($sql);
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
	$sql = "INSERT INTO ".TB_PREFIX."products
		(`description`,`unit_price`,`notes`,`enabled`,`visible`,`custom_field1`,`custom_field2`,`custom_field3`,`custom_field4`) 
		VALUES('$description','$unit_price','$notes',$enabled,$visible,'$custom_field1','$custom_field2','$custom_field3','$custom_field4');";
	
	return mysqlQuery($sql);
}*/


function insertProduct($enabled=1,$visible=1) {
	if(isset($_POST['enabled'])) {
		$enabled=$_POST['enabled'];
	}
	
	$sql = "INSERT into
			".TB_PREFIX."products
		VALUES
			(	
				NULL,
				'$_POST[description]',
				'$_POST[unit_price]',
				'$_POST[custom_field1]',
				'$_POST[custom_field2]',
				'$_POST[custom_field3]',
				'$_POST[custom_field4]',
				'$_POST[notes]',
				'$enabled',
				'$visible'
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
	return sql2array($sql);
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
function getDefaultLanguage() {
	
	global $LANG;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'language')";
	$query = mysqlQuery($sql) or die(mysql_error());
	$entry = mysql_fetch_array($query);
	return $entry['value'];
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
	//echo $sql;
	
	$query  = mysqlQuery($sql) or die(mysql_error());

	//print_r($query);
	$invoice = mysql_fetch_array($query);
	
	//print_r($invoice);
	//exit();
	
	$invoice['date'] = date( $config['date_format'], strtotime( $invoice['date'] ) );
	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['total'] = getInvoiceTotal($invoice['id']);
	$invoice['total_format'] = round($invoice['total'],2);
	$invoice['paid'] = calc_invoice_paid($invoice['id']);
	$invoice['paid_format'] = round($invoice['paid'],2);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];

	
	#invoice total tax
	$sql ="SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =$id";
	$query = mysqlQuery($sql) or die(mysql_error());
	$result = mysql_fetch_array($query);
	//$invoice['total'] = round($result['total'],2);
	$invoice['total_tax'] = round($result['total_tax'],2);
	
	return $invoice;
}


function getInvoiceItems($id) {
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id =$id";
	$query = mysqlQuery($sql);
	
	$invoiceItems = null;
	
	for($i=0;$invoiceItem = mysql_fetch_array($query);$i++) {
	
		$invoiceItem['quantity_formatted'] = round($invoiceItem['quantity'],2);
		$invoiceItem['unit_price'] = round($invoiceItem['unit_price'],2);
		$invoiceItem['tax_amount'] = round($invoiceItem['tax_amount'],2);
		$invoiceItem['gross_total'] = round($invoiceItem['gross_total'],2);
		$invoiceItem['total'] = round($invoiceItem['total'],2);
		
		$p_id = $invoiceItem['product_id'];
		$invoiceItem['product'] = getProduct($p_id);	
		
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
				NULL,
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
	$sql = "INSERT INTO ".TB_PREFIX."customers VALUES (NULL,'$attention', '$name','$street_address','$street_address2','$city','$state','$zip_code','$country','$phone', '$mobile_phone', '$fax', '$email', '$notes', '$custom_field1', '$custom_field2', '$custom_field3', '$custom_field4', '$enabled')";
	
	return mysqlQuery($sql);
	
}

function searchCustomers($search) {
	$sql = "SELECT * FROM  `".TB_PREFIX."customers` WHERE  `name` LIKE  '%$search%'";

	return sql2array($sql);
}	

function getInvoices(&$query) {
	global $config;
	$invoice = null;

	if($invoice =  mysql_fetch_array($query)) {

		$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['date'] ) );
		$invoice['date'] = date( $config['date_format'], strtotime( $invoice['date'] ) );
			
		#invoice total total - start
		$invoice['total'] = getInvoiceTotal($invoice['id']);
		$invoice['total_format'] = round($invoice['total'],2);
		#invoice total total - end
		
		#amount paid calc - start
		$invoice['paid'] = calc_invoice_paid($invoice['id']);
		$invoice['paid_format'] = round($invoice['paid'],2);
		#amount paid calc - end
		
		#amount owing calc - start
		$invoice['owing'] = $invoice['total'] - $invoice['paid'];
		$invoice['owing_format'] = round($invoice['total'] - $invoice['paid'],2);
		#amount owing calc - end
	}
	return $invoice;
}

function getCustomerInvoices($id) {
	
	$invoices = null;
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE customer_id =$id  ORDER BY id DESC";
	return sql2array($sql);
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
	
	global $LANG; // must this be here?	
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled != 0 ORDER BY name";
	return sql2array($sql);
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
			NULL,
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
	$sql = "SELECT * FROM  `".TB_PREFIX."menu` WHERE enabled = 1 ORDER BY parentid,  `order`";
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
	FROM ".TB_PREFIX."biller b, ".TB_PREFIX."invoices i, ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_type t
	WHERE b.name LIKE  '%$biller%'
	AND c.name LIKE  '%$customer%' 
	AND i.biller_id = b.id 
	AND i.customer_id = c.id
	AND i.type_id = t.inv_ty_id";
	return mysqlQuery($sql);
}

function searchInvoiceByDate($startdate,$enddate) {
	$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date,i.type_id AS type_id, t.inv_ty_description as type
	FROM ".TB_PREFIX."biller b, ".TB_PREFIX."invoices i, ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_type t
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

function checkTableExists($table)
{
	global $LANG;	
	$sql = "SELECT 1 FROM `".$table."` LIMIT 0";

	$resultSql = mysql_query($sql);
	
	if ($resultSql) {
		return true;
	}else {
		return false;
	}
}

function checkFieldExists($table,$field)
{
	global $LANG;	
	
	$sql = "SELECT `".$field."` FROM `".$table."` LIMIT 0";

	$resultSql = mysql_query($sql);
	
	if ($resultSql) {
		return true;
	}else {
		return false;
	}
}

function urlPDF($invoiceID,$invoiceTypeID) 
{

	global $http_auth;
	
	$script = "/index.php?module=invoices&view=templates/template&invoice=$invoiceID&action=view&location=pdf&type=$invoiceTypeID";
	$port = "";
	$dir = dirname($_SERVER['PHP_SELF']);

	//set the port of http(s) section
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
		$_SERVER['FULL_URL'] = "https://";
		if($_SERVER['SERVER_PORT']!="443") {
			$port .= "://" . $_SERVER[’SERVER_PORT’];
		}
	} else {
		$_SERVER['FULL_URL'] = "http://";
		if($_SERVER['SERVER_PORT']!="80") {
			$port = ":" . $_SERVER['SERVER_PORT'];
		}
	}

	//merge it all togehter
	if (isset($_SERVER['HTTP_HOST'])) {
		$_SERVER['FULL_URL'] .= $http_auth.$_SERVER['HTTP_HOST'].$port.$dir.$script;
	} else {
		$_SERVER['FULL_URL'] .= $http_auth.$_SERVER['HTTP_HOST'].$port.$dir.$script;
	}
	
	return $_SERVER['FULL_URL'];

}
?>
