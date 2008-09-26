<?php

if(LOGGING) {
	//Logging connection to prevent mysql_insert_id problems. Need to be called before the second connect...
	$log_dbh = db_connector();
}

$dbh = db_connector();

if ($db_server == 'mysql') {
	//SC: May not really be 5...
	$mysql = 5;
}



function db_connector() {

	global $config;
	/*
	* strip the pdo_ section from the adapter
	*/
	$pdoAdapter = substr($config->database->adapter, 4);
	
	$connlink = new PDO(
					$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
				);

			
	return $connlink;
}

function mysqlQuery($sqlQuery) {
	dbQuery($sqlQuery);
}

/*
 * dbQuery is a variadic function that, in its simplest case, functions as the
 * old mysqlQuery does.  The added complexity comes in that it also handles
 * named parameters to the queries.
 *
 * Examples:
 *  $sth = dbQuery('SELECT b.id, b.name FROM si_biller b WHERE b.enabled');
 *  $tth = dbQuery('SELECT c.name FROM si_customers c WHERE c.id = :id',
 *                 ':id', $id);
 */

function dbQuery($sqlQuery) {
	global $dbh;

	$argc = func_num_args();
	$binds = func_get_args();
	$sth = false;

	// PDO SQL Preparation
	$sth = $dbh->prepare($sqlQuery);
	if ($argc > 1) {
		array_shift($binds);
		for ($i = 0; $i < count($binds); $i++) {
			$sth->bindValue($binds[$i], $binds[++$i]);
		}
	}
	// PDO Execution
	if($sth && $sth->execute()) {
		dbLogger($sqlQuery);
	} else {
		echo "Dude, what happened to your query?:<br><br> ".htmlspecialchars($sqlQuery)."<br />".htmlspecialchars(end($sth->errorInfo()));
	// Earlier implementation did not return the $sth on error
	}
// $sth now has the PDO object or false on error.
		
	return $sth;

}

// Used for logging all queries
function dbLogger($sqlQuery) {
	global $log_dbh;
	global $dbh;
	$userid = 1;		// for now
	
	if(LOGGING && (preg_match('/^\s*select/iD',$sqlQuery) == 0)) {
		// Only log queries that could result in data/database  modification

		$last = null;
		$tth = null;
		$sql = "INSERT INTO ".TB_PREFIX."log (timestamp, userid, sqlquerie, last_id) VALUES (CURRENT_TIMESTAMP , ?, ?, ?)";

		/* SC: Check for the patch manager patch loader.  If a
		 *     patch is being run, avoid $log_dbh due to the
		 *     risk of deadlock.
		 */
		$call_stack = debug_backtrace();
		//SC: XXX Change the number back to 1 if returned to directly
		//    within dbQuery.  The joys of dealing with the call stack.

		if ($call_stack[2]['function'] == 'run_sql_patch') {
		/* Running the patch manager, avoid deadlock */
			$tth = $dbh->prepare($sql);
		} elseif (preg_match('/^(update|insert)/iD', $sqlQuery)) {
			$last = lastInsertId();
			$tth = $log_dbh->prepare($sql);
		} else {
			$tth = $log_dbh->prepare($sql);
		}
		$tth->execute(array($userid, trim($sqlQuery), $last));
		$tth = null;
	}
}

/*
 * lastInsertId returns the id of the most recently inserted row by the session
 * used by $dbh whose id was created by AUTO_INCREMENT (MySQL) or a sequence
 * (PostgreSQL).  This is a convenience function to handle the backend-
 * specific details so you don't have to.
 *
 */
function lastInsertId() {
	global $config;
	global $dbh;
	$pdoAdapter = substr($config->database->adapter, 4);
	
	if ($pdoAdapter == 'pgsql') {
		$sql = 'SELECT lastval()';
	} elseif ($pdoAdapter == 'mysql') {
		$sql = 'SELECT last_insert_id()';
	}
	echo $sql;
	$sth = $dbh->prepare($sql);
	$sth->execute();
	return $sth->fetchColumn();
}

/*
 * _invoice_check_fk performs some manual FK checks on tables that the invoice
 *     table refers to.   Under normal conditions, this function will return
 *     true.  Returning false indicates that if the INSERT or UPDATE were to
 *     proceed, bad data could be written to the database.
 */

// In every instance of insertion into / updation  of the invoice table, we only pick from dropdown boxes which are sourced from the respective lookup tables.
// Hence is this function necessary to be used at all?

function _invoice_check_fk($biller, $customer, $type, $preference) {
	global $dbh;

	//Check biller
	$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'biller WHERE id = :id');
	$sth->execute(array(':id' => $biller));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check customer
	$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'customers WHERE id = :id');
	$sth->execute(array(':id' => $customer));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check invoice type
	$sth = $dbh->prepare('SELECT count(inv_ty_id) FROM '.TB_PREFIX.'invoice_type WHERE inv_ty_id = :id');
	$sth->execute(array(':id' => $type));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check preferences
	$sth = $dbh->prepare('SELECT count(pref_id) FROM '.TB_PREFIX.'preferences WHERE pref_id = :id');
	$sth->execute(array(':id' => $preference));
	if ($sth->fetchColumn() == 0) { return false; }
	
	//All good
	return true;
}

/*
 * _invoice_items_check_fk performs some manual FK checks on tables that the
 *     invoice items table refers to.   Under normal conditions, this function
 *     will return true.  Returning false indicates that if the INSERT or
 *     UPDATE were to proceed, bad data could be written to the database.
 */
function _invoice_items_check_fk($invoice, $product, $tax, $update) {
	global $dbh;

	//Check invoice
	if (is_null($update) || !is_null($invoice)) {
		$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'invoices WHERE id = :id');
		$sth->execute(array(':id' => $invoice));
		if ($sth->fetchColumn() == 0) { return false; }
	}
	//Check product
	$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'products WHERE id = :id');
	$sth->execute(array(':id' => $product));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check tax id
	$sth = $dbh->prepare('SELECT count(tax_id) FROM '.TB_PREFIX.'tax WHERE tax_id = :id');
	$sth->execute(array(':id' => $tax));
	if ($sth->fetchColumn() == 0) { return false; }

	//All good
	return true;
}

function getCustomer($id) {
	global $db_server;
	global $dbh;

	$print_customer = "SELECT * FROM ".TB_PREFIX."customers WHERE id = :id";
	$sth = dbQuery($print_customer, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getBiller($id) {
	global $LANG;
	global $dbh;

	$print_biller = "SELECT * FROM ".TB_PREFIX."biller WHERE id = :id";
	$sth = dbQuery($print_biller, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	$biller = $sth->fetch();
	$biller['wording_for_enabled'] = $biller['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $biller;
}

function getPreference($id) {
	global $LANG;
	global $dbh;

	$print_preferences = "SELECT * FROM ".TB_PREFIX."preferences WHERE pref_id = :id";
	$sth = dbQuery($print_preferences, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	$preference = $sth->fetch();
	$preference['enabled'] = $preference['pref_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $preference;
}

function getSQLPatches() {
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."sql_patchmanager ORDER BY sql_release";                  
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetchAll();
}

function getPreferences() {
	global $LANG;
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences ORDER BY pref_description";
	$sth  = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$preferences = null;
	
	for($i=0;$preference = $sth->fetch();$i++) {
		
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
	global $dbh;
	global $db_server;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_enabled != 0 ORDER BY tax_description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_enabled ORDER BY tax_description";
	}
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$taxes = null;
	
	for($i=0;$tax = $sth->fetch();$i++) {
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
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences WHERE pref_enabled ORDER BY pref_description";
	$sth  = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));

	return $sth->fetchAll();
}

function getCustomFieldLabels() {
	global $LANG;
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."custom_fields ORDER BY cf_custom_field";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	for($i=0;$customField = $sth->fetch();$i++) {
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
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."biller ORDER BY name";
	$sth  = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$billers = null;
	
	for($i=0;$biller = $sth->fetch();$i++) {
		
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
	global $dbh;
	global $db_server;

	$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE enabled != 0 ORDER BY name";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE enabled ORDER BY name";
	}
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	return $sth->fetchAll();
}

function getTaxRate($id) {
	global $LANG;
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_id = :id";
	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$tax = $sth->fetch();
	$tax['enabled'] = $tax['tax_enabled'] == 1 ? $LANG['enabled']:$LANG['disabled'];
	
	return $tax;
}

function getPaymentType($id) {
	global $LANG;
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_id = :id";
	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	$paymentType = $sth->fetch();
	$paymentType['enabled'] = $paymentType['pt_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	
	return $paymentType;
}

function getPayment($id) {
	global $config;
	global $dbh;

	$sql = "SELECT ap.*, c.name AS customer, b.name AS biller FROM ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b WHERE ap.ac_inv_id = iv.id AND iv.customer_id = c.id AND iv.biller_id = b.id AND ap.id = :id";

	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	$payment = $sth->fetch();
	$payment['date'] = siLocal::date($payment['ac_date']);
	return $payment;
}

function getInvoicePayments($id) {
	$sql = "SELECT ap.*, c.name as cname, b.name as bname from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b where ap.ac_inv_id = iv.id and iv.customer_id = c.id and iv.biller_id = b.id and ap.ac_inv_id = :id ORDER BY ap.id DESC";
	return dbQuery($sql, ':id', $id);
}

function getCustomerPayments($id) {
	$sql = "SELECT ap.*, c.name as cname, b.name as bname from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b where ap.ac_inv_id = iv.id and iv.customer_id = c.id and iv.biller_id = b.id and c.id = :id ORDER BY ap.id DESC";
	return dbQuery($sql, ':id', $id);
}

function getPayments() {
	$sql = "SELECT ap.*, c.name as cname, b.name as bname from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b WHERE ap.ac_inv_id = iv.id AND iv.customer_id = c.id and iv.biller_id = b.id ORDER BY ap.id DESC";
	
	return dbQuery($sql);
}

function progressPayments($sth) {
	$payments = null;

	for($i=0;$payment = $sth->fetch();$i++) {

		$sql = "SELECT pt_description FROM ".TB_PREFIX."payment_types WHERE pt_id = :id";
		$tth = dbQuery($sql, ':id', $payment['ac_payment_type']);

		$pt = $tth->fetch();
		
		$payments[$i] = $payment;
		$payments[$i]['description'] = $pt['pt_description'];
		
	}
	
	return $payments;
}

function getPaymentTypes() {
	global $LANG;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types ORDER BY pt_description";
	$sth = dbQuery($sql);
	
	$paymentTypes = null;

	for ($i=0;$paymentType = $sth->fetch();$i++) {
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
	global $dbh;
	global $db_server;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_enabled != 0 ORDER BY pt_description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_enabled ORDER BY pt_description";
	}
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$paymentTypes = null;

	for ($i=0;$paymentType = $sth->fetch();$i++) {
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
	global $dbh;

	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id";
	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	$product = $sth->fetch();
	$product['wording_for_enabled'] = $product['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $product;
}

/*function insertProduct($description,$unit_price,$enabled=1,$visible=1,$notes="",$custom_field1="",$custom_field2="",$custom_field3="",$custom_field4="") {
	$sql = "INSERT INTO ".TB_PREFIX."products
		(`description`,`unit_price`,`notes`,`enabled`,`visible`,`custom_field1`,`custom_field2`,`custom_field3`,`custom_field4`) 
		VALUES('$description','$unit_price','$notes',$enabled,$visible,'$custom_field1','$custom_field2','$custom_field3','$custom_field4');";
	
	return mysqlQuery($sql);
}*/

function insertProductComplete($enabled=1,$visible=1,$description, 
		$unit_price, $custom_field1 = NULL, $custom_field2, $custom_field3, $custom_field4, $notes) {


	/*if(isset($enabled)) {
		$enabled=$enabled;
	}*/
	
	if ($db_server == 'pgsql') {
		$sql = "INSERT into
			".TB_PREFIX."products
			(description, unit_price, custom_field1, custom_field2,
			custom_field3, custom_field4, notes, enabled, visible)
		VALUES
			(	
				:description, :unit_price, :custom_field1,
				:custom_field2, :custom_field3, :custom_field4,
				:notes, :enabled, :visible
			)";
	} else {
		$sql = "INSERT into
			".TB_PREFIX."products
			(
				description, unit_price, custom_field1, custom_field2,
				custom_field3, custom_field4, notes, enabled, visible
			)
		VALUES
			(	
				:description,
				:unit_price,
				:custom_field1,
				:custom_field2,
				:custom_field3,
				:custom_field4,
				:notes,
				:enabled,
				:visible
			)";
	}
	return dbQuery($sql,
		':description', $description,
		':unit_price', $unit_price,
		':custom_field1', $custom_field1,
		':custom_field2', $custom_field2,
		':custom_field3', $custom_field3,
		':custom_field4', $custom_field4,
		':notes', "".$notes,
		':enabled', $enabled,
		':visible', $visible
		);
}


function insertProduct($enabled=1,$visible=1) {
	return insertProductComplete($enabled, $visible, $_POST['description'], $_POST['unit_price'],
		$_POST['custom_field1'],
		$_POST['custom_field2'],
		$_POST['custom_field3'],
		$_POST['custom_field4'],
		$_POST['notes'],
		$enabled,
		$visible
		);
}

function updateProduct() {
	
	$sql = "UPDATE ".TB_PREFIX."products
			SET
				description = :description,
				enabled = :enabled,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				unit_price = :unit_price
			WHERE
				id = :id";

	return dbQuery($sql,
		':description', $_POST[description],
		':enabled', $_POST[enabled],
		':notes', $_POST[notes],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':unit_price', $_POST[unit_price],
		':id', $_GET[id]
		);
}

function getProducts() {
	global $LANG;
	global $dbh;
	global $db_server;
	
	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE visible = 1 ORDER BY description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."products WHERE visible ORDER BY description";
	}
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$products = null;
	
	for($i=0;$product = $sth->fetch();$i++) {
		
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
	global $dbh;
	global $db_server;
	
	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE enabled ORDER BY description";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	return $sth->fetchAll();
}

function getTaxes() {
	global $LANG;
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax ORDER BY tax_description";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$taxes = null;
	
	for($i=0;$tax = $sth->fetch();$i++) {
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
	global $dbh;
	
	$sql = "SELECT *,c.name AS name FROM ".TB_PREFIX."customers c, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'customer' AND c.id = s.value)";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultPaymentType() {
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'payment_type' AND p.pt_id = s.value)";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultPreference() {
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'preference' AND p.pref_id = s.value)";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultBiller() {
	global $dbh;
	
	$sql = "SELECT *,b.name AS name FROM ".TB_PREFIX."biller b, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'biller' AND b.id = s.value )";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultTax() {
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax t, ".TB_PREFIX."system_defaults s WHERE (s.name = 'tax' AND t.tax_id = s.value)";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultDelete() {
	global $LANG;
	global $dbh;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'delete')";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$array = $sth->fetch();
	$delete = $array['value']==1?$LANG['enabled']:$LANG['disabled'];
	return $delete;
}

function getDefaultLogging() {
	global $LANG;
	global $dbh;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'logging')";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$array = $sth->fetch();
	$delete = $array['value']==1?$LANG['enabled']:$LANG['disabled'];
	return $delete;
}

function getDefaultLanguage() {
	global $LANG;
	global $dbh;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'language')";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$entry = $sth->fetch();
	return $entry['value'];
}

function getInvoiceTotal($invoice_id) {
	global $LANG;
	
	$sql ="SELECT SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :invoice_id";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id);
	$res = $sth->fetch();
	//echo "TOTAL".$res['total'];
	return $res['total'];
}

function setInvoiceStatus($invoice, $status){
	global $dbh;

	$sql = "UPDATE " . TB_PREFIX . "invoices SET status_id = :status WHERE id = :id";
	$sth  = dbQuery($sql, ':status', $status, ':id', $invoice) or die(htmlspecialchars(end($dbh->errorInfo())));
}

function getInvoice($id) {
	global $dbh;
	global $config;
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE id = :id";
	//echo $sql;
	
	$sth  = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));

	//print_r($query);
	$invoice = $sth->fetch();
	
	//print_r($invoice);
	//exit();
	
	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['date'] = siLocal::date( $invoice['date'] );
	$invoice['total'] = getInvoiceTotal($invoice['id']);
	$invoice['paid'] = calc_invoice_paid($invoice['id']);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];

	
	#invoice total tax
	$sql ="SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id";
	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	$result = $sth->fetch();
	//$invoice['total'] = number_format($result['total'],2);
	$invoice['total_tax'] = $result['total_tax'];
	
	return $invoice;
}

class invoice {
	function getInvoiceItems($id) {
	
		$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id";
		$sth = dbQuery($sql, ':id', $id);
		
		$invoiceItems = null;
		
		for($i=0;$invoiceItem = $sth->fetch();$i++) {
		
			$invoiceItem['quantity'] = $invoiceItem['quantity'];
			$invoiceItem['unit_price'] = $invoiceItem['unit_price'];
			$invoiceItem['tax_amount'] = $invoiceItem['tax_amount'];
			$invoiceItem['gross_total'] = $invoiceItem['gross_total'];
			$invoiceItem['total'] = $invoiceItem['total'];
			
			$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id";
			$tth = dbQuery($sql, ':id', $invoiceItem['product_id']) or die(htmlspecialchars(end($dbh->errorInfo())));
			$invoiceItem['product'] = $tth->fetch();	
			
			$invoiceItems[$i] = $invoiceItem;
		}
		
		return $invoiceItems;
	}
}

function getSystemDefaults() {
	global $dbh;
	
	$print_defaults = "SELECT * FROM ".TB_PREFIX."system_defaults";
	$sth = dbQuery($print_defaults) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$defaults = null;
	$default = null;
	
	
	while($default = $sth->fetch()) {
		$defaults["$default[name]"] = $default['value'];
	}

	return $defaults;
}

function updateDefault($name,$value) {
	
	$sql = "UPDATE ".TB_PREFIX."system_defaults SET value =  :value WHERE name = :name"; 
	//echo $sql;
	if (dbQuery($sql, ':value', $value, ':name', $name)) {
		return true;
	}
	return false;
}

function getInvoiceType($id) {
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :id";
	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	return $sth->fetch();
}

function insertBiller() {
	global $db_server;
	
	if ($db_server == 'pgsql') {
		$sql = "INSERT into
			".TB_PREFIX."biller (
				name, street_address, street_address2, city,
				state, zip_code, country, phone, mobile_phone,
				fax, email, logo, footer, notes, custom_field1,
				custom_field2, custom_field3, custom_field4,
				enabled
			)
		VALUES
			(
				:name, :street_address, :street_address2, :city,
				:state, :zip_code, :country, :phone,
				:mobile_phone, :fax, :email, :logo, :footer,
				:notes, :custom_field1, :custom_field2,
				:custom_field3, :custom_field4, :enabled
			 )";
	} else {
		$sql = "INSERT into
			".TB_PREFIX."biller
		VALUES
			(
				NULL,
				1,
				:name,
				:street_address,
				:street_address2,
				:city,
				:state,
				:zip_code,
				:country,
				:phone,
				:mobile_phone,
				:fax,
				:email,
				:logo,
				:footer,
				:notes,
				:custom_field1,
				:custom_field2,
				:custom_field3,
				:custom_field4,
				:enabled
			 )";
	}


	return dbQuery($sql,
		':name', $_POST[name],
		':street_address', $_POST[street_address],
		':street_address2', $_POST[street_address2],
		':city', $_POST[city],
		':state', $_POST[state],
		':zip_code', $_POST[zip_code],
		':country', $_POST[country],
		':phone', $_POST[phone],
		':mobile_phone', $_POST[mobile_phone],
		':fax', $_POST[fax],
		':email', $_POST[email],
		':logo', $_POST[logo],
		':footer', $_POST[footer],
		':notes', $_POST[notes],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':enabled', $_POST[enabled]
		);
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
				name = :name,
				street_address = :street_address,
				street_address2 = :street_address2,
				city = :city,
				state = :state,
				zip_code = :zip_code,
				country = :country,
				phone = :phone,
				mobile_phone = :mobile_phone,
				fax = :fax,
				email = :email,
				logo = :logo,
				footer = :footer,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				enabled = :enabled
			WHERE
				id = :id";
	return dbQuery($sql,
		':name', $_POST[name],
		':street_address', $_POST[street_address],
		':street_address2', $_POST[street_address2],
		':city', $_POST[city],
		':state', $_POST[state],
		':zip_code', $_POST[zip_code],
		':country', $_POST[country],
		':phone', $_POST[phone],
		':mobile_phone', $_POST[mobile_phone],
		':fax', $_POST[fax],
		':email', $_POST[email],
		':logo', $_POST[logo],
		':footer', $_POST[footer],
		':notes', $_POST[notes],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':enabled', $_POST[enabled],
		':id', $_GET[id]
		);
}

function updateCustomer() {
	

	$sql = "
			UPDATE
				".TB_PREFIX."customers
			SET
				name = :name,
				attention = :attention,
				street_address = :street_address,
				street_address2 = :street_address2,
				city = :city,
				state = :state,
				zip_code = :zip_code,
				country = :country,
				phone = :phone,
				mobile_phone = :mobile_phone,
				fax = :fax,
				email = :email,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				enabled = :enabled
			WHERE
				id = :id";

	return dbQuery($sql,
		':name', $_POST[name],
		':attention', $_POST[attention],
		':street_address', $_POST[street_address],
		':street_address2', $_POST[street_address2],
		':city', $_POST[city],
		':state', $_POST[state],
		':zip_code', $_POST[zip_code],
		':country', $_POST[country],
		':phone', $_POST[phone],
		':mobile_phone', $_POST[mobile_phone],
		':fax', $_POST[fax],
		':email', $_POST[email],
		':notes', $_POST[notes],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':enabled', $_POST[enabled],
		':id', $_GET['customer']
		);
}

function insertCustomer() {
	global $db_server;

	extract( $_POST );
	$sql = "INSERT INTO ".TB_PREFIX."customers VALUES ('',1  ,:attention, :name, :street_address, :street_address2, :city, :state, :zip_code, :country, :phone, :mobile_phone, :fax, :email, :notes, :custom_field1, :custom_field2, :custom_field3, :custom_field4, :enabled)";
	
	if ($db_server == 'pgsql') {
		$sql = "INSERT INTO ".TB_PREFIX."customers (
			attention, name, street_address, street_address2,
			city, state, zip_code, country, phone, mobile_phone,
			fax, email, notes, custom_field1, custom_field2,
			custom_field3, custom_field4, enabled)
		VALUES (
			:attention, :name, :street_address, :street_address2,
			:city, :state, :zip_code, :country, :phone,
			:mobile_phone, :fax, :email, :notes, :custom_field1,
			:custom_field2, :custom_field3, :custom_field4, :enabled)";
	}
	
	return dbQuery($sql,
		':attention', $attention,
		':name', $name,
		':street_address', $street_address,
		':street_address2', $street_address2,
		':city', $city,
		':state', $state,
		':zip_code', $zip_code,
		':country', $country,
		':phone', $phone,
		':mobile_phone', $mobile_phone,
		':fax', $fax,
		':email', $email,
		':notes', $notes,
		':custom_field1', $custom_field1,
		':custom_field2', $custom_field2,
		':custom_field3', $custom_field3,
		':custom_field4', $custom_field4,
		':enabled', $enabled
		);
	
}

function searchCustomers($search) {
	global $db_server;

	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE name LIKE :search";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE name ILIKE :search";
	}
	$sth = dbQuery($sql, ':search', "%$search%");
	
	$customers = null;
	
	for($i=0; $customer = $sth->fetch(); $i++) {
		$customers[$i] = $customer;
	}
	//echo $sql;
	
	//print_r($customers);
	return $customers;
}

function getInvoices(&$sth) {
	global $config;
	$invoice = null;

	if($invoice = $sth->fetch()) {

		$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['date'] ) );
		$invoice['date'] = siLocal::date($invoice['date']);
			
		#invoice total total - start
		$invoice['total'] = getInvoiceTotal($invoice['id']);
		#invoice total total - end
		
		#amount paid calc - start
		$invoice['paid'] = calc_invoice_paid($invoice['id']);
		#amount paid calc - end
		
		#amount owing calc - start
		$invoice['owing'] = $invoice['total'] - $invoice['paid'];
		#amount owing calc - end
	}
	return $invoice;
}

function getCustomerInvoices($id) {
	global $dbh;
	global $config;

// tested for MySQL	
	$sql = "SELECT	i.id, i.date, i.type_id, 
		(SELECT sum( COALESCE(ii.total, 0)) FROM " . TB_PREFIX . "invoice_items ii where ii.invoice_id = i.id) As invd,
		(SELECT sum( COALESCE(ap.ac_amount, 0)) FROM " . TB_PREFIX . "account_payments ap where ap.ac_inv_id = i.id) As pmt,
		(SELECT COALESCE(invd, 0)) As total, 
		(SELECT COALESCE(pmt, 0)) As paid, 
		(select (total - paid)) as owing 
	FROM " . TB_PREFIX . "invoices i 
	WHERE i.customer_id = :id
	ORDER BY i.id DESC;";	

	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));

	$invoices = null;
	while ($invoice = $sth->fetch()) {
		$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['date'] ) );
		$invoice['date'] = siLocal::date( $invoice['date'] );
		$invoices[] = $invoice;
	}
	return $invoices;

}

function getCustomers() {
	global $dbh;
	global $LANG;
	
	$customer = null;
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers";
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));

	$customers = null;

	for($i=0; $customer = $sth->fetch(); $i++) {
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
	global $dbh;
	global $db_server;
	
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled != 0 ORDER BY name";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled ORDER BY name";
	}
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));

	return $sth->fetchAll();
}

function getTopDebtor() {
  global $db_server;
  global $dbh;
  global $mysql;

  $debtor = null;

  #Largest debtor query - start
  
	$sql = "SELECT	
	        	c.id as \"CID\",
	        	c.name as \"Customer\",
	        	sum(ii.total) as \"Total\",
	        	(select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Paid\",
	        	sum(ii.total) - (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Owing\"
	FROM
	        ".TB_PREFIX."customers c INNER JOIN
		".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	GROUP BY
		\"CID\", iv.customer_id, c.id, c.name
	ORDER BY
	        \"Owing\" DESC
	LIMIT 1;
	";

	$sth = dbQuery($sql) or die(end($dbh->errorInfo()));

	$debtor = $sth->fetch();
  
  #Largest debtor query - end
  return $debtor;
}

function getTopCustomer() {
  global $db_server;
  global $dbh;
  global $mysql;

  $customer = null;

  #Top customer query - start
  
	$sql2 = "SELECT
			c.id as \"CID\",
	        	c.name as \"Customer\",
       			sum(ii.total) as \"Total\",
	        	(select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Paid\",
	        	sum(ii.total) - (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Owing\"

	FROM
       		".TB_PREFIX."customers c INNER JOIN
		".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	GROUP BY
	        \"CID\", iv.customer_id, \"Customer\"
	ORDER BY 
		\"Total\" DESC
	LIMIT 1;
";

	$tth = dbQuery($sql2) or die(end($dbh->errorInfo()));

	$customer = $tth->fetch();
 
  #Top customer query - end
  return $customer;
}

function getTopBiller() {
  global $db_server;
  global $dbh;
  global $mysql;

  $biller = null;

  #Top biller query - start
 	
	$sql3 = "SELECT
		b.name,  
		sum(ii.total) as Total 
	FROM 
		".TB_PREFIX."biller b INNER JOIN
		".TB_PREFIX."invoices iv ON (b.id = iv.biller_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	GROUP BY b.name
	ORDER BY Total DESC
	LIMIT 1;
	";

	$uth = dbQuery($sql3) or die(end($dbh->errorInfo()));

	$biller = $uth->fetch();
  
  #Top biller query - start
  return $biller;
}

function insertTaxRate() {
	global $LANG;

	$sql = "INSERT into ".TB_PREFIX."tax
				(tax_description, tax_percentage, tax_enabled)
			VALUES
				(:description, :percent, :enabled)";
	
	$display_block = $LANG['save_tax_rate_success'];
	if (!(dbQuery($sql,
		':description', $_POST['tax_description'],
		':percent', $_POST['tax_percentage'],
		':enabled', $_POST['tax_enabled']))) {
		$display_block = $LANG['save_tax_rate_failure'];
	}
	return $display_block;
}

function updateTaxRate() {
	global $LANG;
	
	$sql = "UPDATE
				".TB_PREFIX."tax
			SET
				tax_description = :description,
				tax_percentage = :percentage,
				tax_enabled = :enabled
			WHERE
				tax_id = :id";

	$display_block = $LANG['save_tax_rate_success'];
	if (!(dbQuery($sql,
		':description', $_POST['tax_description'],
	  	':percentage', $_POST['tax_percentage'],
	  	':enabled', $_POST['tax_enabled'],
	  	':id', $_GET['id']))) {
		$display_block = $LANG['save_tax_rate_failure'];
	}
	return $display_block;
}

function insertInvoice($type) {
	global $dbh;
	global $db_server;
	
	if ($db_server == 'mysql' && !_invoice_check_fk(
		$_POST['biller_id'], $_POST['customer_id'],
		$type, $_POST['preference_id'])) {
		return null;
	}
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
			:biller_id,
			:customer_id,
			:type,
			:preference_id,
			:date,
			:note,
			:customField1,
			:customField2,
			:customField3,
			:customField4
			)";
	if ($db_server == 'pgsql') {
		$sql = "INSERT 
				INTO
			".TB_PREFIX."invoices (
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
				:biller_id,
				:customer_id,
				:type,
				:preference_id,
				:date,
				:note,
				:customField1,
				:customField2,
				:customField3,
				:customField4
				)";
	}
	//echo $sql;
	return dbQuery($sql,
		':biller_id', $_POST[biller_id],
		':customer_id', $_POST[customer_id],
		':type', $type,
		':preference_id', $_POST[preference_id],
		':date', $_POST[date],
		':note', $_POST[note],
		':customField1', $_POST[customField1],
		':customField2', $_POST[customField2],
		':customField3', $_POST[customField3],
		':customField4', $_POST[customField4]
		);
}

function updateInvoice($invoice_id) {
	
	if ($db_server == 'mysql' && !_invoice_check_fk(
		$_POST['biller_id'], $_POST['customer_id'],
		$type, $_POST['preference_id'])) {
		return null;
	}
	$sql = "UPDATE
			".TB_PREFIX."invoices
		SET
			biller_id = :biller_id,
			customer_id = :customer_id,
			preference_id = :preference_id,
			date = :date,
			note = :note,
			custom_field1 = :customField1,
			custom_field2 = :customField2,
			custom_field3 = :customField3,
			custom_field4 = :customField4
		WHERE
			id = :invoice_id";
			
	return dbQuery($sql,
		':biller_id', $_POST['biller_id'],
		':customer_id', $_POST['customer_id'],
		':preference_id', $_POST['preference_id'],
		':date', $_POST['date'],
		':note', $_POST['note'],
		':customField1', $_POST['customField1'],
		':customField2', $_POST['customField2'],
		':customField3', $_POST['customField3'],
		':customField4', $_POST['customField4'],
		':invoice_id', $invoice_id
		);
}

function insertInvoiceItem($invoice_id,$quantity,$product_id,$tax_id,$description="", $unit_price="") {
	
	$tax = getTaxRate($tax_id);
	//$product = getProduct($product_id);
	//print_r($product);
	$actual_tax = $tax['tax_percentage']  / 100 ;
	$total_invoice_item_tax = $unit_price * $actual_tax;
	$tax_amount = $total_invoice_item_tax * $quantity;
	$total_invoice_item = $total_invoice_item_tax + $unit_price;	
	$total = $total_invoice_item * $quantity;
	$gross_total = $unit_price  * $quantity;
	
	if ($db_server == 'mysql' && !_invoice_items_check_fk(
		$invoice_id, $product_id, $tax['tax_id'])) {
		return null;
	}
	$sql = "INSERT INTO ".TB_PREFIX."invoice_items (invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES (:invoice_id, :quantity, :product_id, :unit_price, :tax_id, :tax_percentage, :tax_amount, :gross_total, :description, :total)";

	//echo $sql;
	return dbQuery($sql,
		':invoice_id', $invoice_id,
		':quantity', $quantity,
		':product_id', $product_id,
		':unit_price', $unit_price,
		':tax_id', $tax[tax_id],
		':tax_percentage', $tax[tax_percentage],
		':tax_amount', $tax_amount,
		':gross_total', $gross_total,
		':description', $description,
		':total', $total
		);

}

function updateInvoiceItem($id,$quantity,$product_id,$tax_id,$description,$unit_price) {

	//$product = getProduct($product_id);
	$tax = getTaxRate($tax_id);
	
	$total_invoice_item_tax = $unit_price * $tax['tax_percentage'] / 100;	//:100?
	$tax_amount = $total_invoice_item_tax * $quantity;
	$total_invoice_item = $total_invoice_item_tax + $unit_price;
	$total = $total_invoice_item * $quantity;
	$gross_total = $unit_price * $quantity;
	
	if ($db_server == 'mysql' && !_invoice_items_check_fk(
		null, $product_id, $tax_id, 'update')) {
		return null;
	}

	$sql = "UPDATE ".TB_PREFIX."invoice_items 
	SET quantity =  :quantity,
	product_id = :product_id,
	unit_price = :unit_price,
	tax_id = :tax_id,
	tax = :tax,
	tax_amount = :tax_amount,
	gross_total = :gross_total,
	description = :description,
	total = :total			
	WHERE id = :id";
	
	//echo $sql;
		
	return dbQuery($sql,
		':quantity', $quantity,
		':product_id', $product_id,
		':unit_price', $unit_price,
		':tax_id', $tax_id,
		':tax', $tax[tax_percentage],
		':tax_amount', $tax_amount,
		':gross_total', $gross_total,
		':description', $description,
		':total', $total,
		':id', $id
		);
}

/*
function getMenuStructure() {
	global $LANG;
	global $dbh;
	global $db_server;
	$sql = "SELECT * FROM ".TB_PREFIX."menu WHERE enabled = 1 ORDER BY parentid, `order`";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."menu WHERE enabled ORDER BY parentid, \"order\"";
	}
	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$menu = null;
	
	while($res = $sth->fetch()) {
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

	foreach($menu[$id] as $tempentry) {
		for($i=0;$i<$depth;$i++) {
			//echo "&nbsp;&nbsp;&nbsp;";
		}
		echo "
		<li><a href='".$tempentry[link]."'>".htmlspecialchars($tempentry[name])."</a>
		";
		
		if(isset($menu[$tempentry["id"]])) {
			echo "<ul>";
			printEntries($menu,$tempentry["id"],$depth+1);
			echo "</ul>";
		}
		echo "</li>\n";
	}
}
*/

function searchBillerAndCustomerInvoice($biller,$customer) {
	global $db_server;

	$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date, i.type_id AS type_id,t.inv_ty_description as type
	FROM ".TB_PREFIX."biller b, ".TB_PREFIX."invoices i, ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_type t
	WHERE b.name LIKE :biller
	AND c.name LIKE :customer 
	AND i.biller_id = b.id 
	AND i.customer_id = c.id
	AND i.type_id = t.inv_ty_id";
	if ($db_server == 'pgsql') {
		$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date, i.type_id AS type_id,t.inv_ty_description as type
		FROM ".TB_PREFIX."biller b, ".TB_PREFIX."invoices i, ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_type t
		WHERE b.name ILIKE :biller
		AND c.name ILIKE :customer 
		AND i.biller_id = b.id 
		AND i.customer_id = c.id
		AND i.type_id = t.inv_ty_id";
	}
	return dbQuery($sql,
		':biller', "%$biller%",
		':customer', "%$customer%"
		);
}

function searchInvoiceByDate($startdate,$enddate) {
	$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date,i.type_id AS type_id, t.inv_ty_description as type
	FROM ".TB_PREFIX."biller b, ".TB_PREFIX."invoices i, ".TB_PREFIX."customers c, ".TB_PREFIX."invoice_type t
	WHERE i.date >= :startdate 
	AND i.date <= :enddate
	AND i.biller_id = b.id 
	AND i.customer_id = c.id
	AND i.type_id = t.inv_ty_id";
	return dbQuery($sql,
		':startdate', $startdate,
		':enddate', $enddate
		);
}

/*
 * delete attempts to delete rows from the database.  This function currently
 * allows for the deletion of invoices, invoice_items, and products entries,
 * all other $module values will fail.  $idField is also checked on a per-table
 * basis, i.e. invoice_items can be either "id" or "invoice_id" while products
 * can only be "id".
 *
 * Invalid $module or $idFields values return false, as do calls that would fail
 * foreign key checks.  Otherwise, the value returned by dbQuery's deletion
 * attempt is returned.
 */

function delete($module,$idField,$id) {
	global $dbh;

	$lctable = strtolower($module);
	$s_idField = ''; // Presetting the whitelisted column to fail 

	/*
	 * SC: $valid_tables contains the base names of all tables that can
	 *     have rows deleted using this function.  This is used for
	 *     whitelisting deletion targets.
	 */
	$valid_tables = array('invoices', 'invoice_items', 'products');

	if (in_array($lctable, $valid_tables)) {
		// A quick once-over on the dependencies of the possible tables
		if ($lctable == 'invoice_items') {
			// Not required by any FK relationships
			if (!in_array($idField, array('id', 'invoice_id'))) {
				// Fail, invalid identity field
				return false;
			} else {
				$s_idField = $idField;
			}
		} elseif ($lctable == 'products') {
			// Check for use of product
			$sth = $dbh->prepare('SELECT count(*)
				FROM '.TB_PREFIX.'invoice_items
				WHERE product_id = :id');
			$sth->execute(array(':id' => $id));
			$ref = $sth->fetch();
			if ($sth->fetchColumn() != 0) {
				// Fail, product still in use
				return false;
			}
			$sth = null;

			if (!in_array($idField, array('id'))) {
				// Fail, invalid identity field
				return false;
			} else {
				$s_idField = $idField;
			}
		} elseif ($lctable == 'invoices') {
			// Check for existant payments and line items
			$sth = $dbh->prepare('SELECT count(*) FROM (
				SELECT id FROM '.TB_PREFIX.'invoice_items
				WHERE invoice_id = :id
				UNION ALL
				SELECT id FROM '.TB_PREFIX.'account_payments
				WHERE ac_inv_id = :id) x');
			$sth->execute(array(':id' => $id));
			if ($sth->fetchColumn() != 0) {
				// Fail, line items or payments still exist
				return false;
			}
			$sth = null;

			//SC: Later, may accept other values for $idField
			if (!in_array($idField, array('id'))) {
				// Fail, invalid identity field
				return false;
			} else {
				$s_idField = $idField;
			}
		} else {
			// Fail, no checks for this table exist yet
			return false;
		}
	} else {
		// Fail, invalid table name
		return false;
	}

	if ($s_idField == '') {
		// Fail, column whitelisting not performed
		return false;
	}
		
	// Tablename and column both pass whitelisting and FK checks
	$sql = "DELETE FROM ".TB_PREFIX."$module WHERE $s_idField = :id";
	return dbQuery($sql, ':id', $id);
}

function maxInvoice() {

	global $LANG;	
	$sql = "SELECT max(id) as maxId FROM ".TB_PREFIX."invoices";

	$sth = dbQuery($sql);
	return $sth->fetch();
	
//while ($Array_max = mysql_fetch_array($result_max) ) {
//$max_invoice_id = $Array_max['max_inv_id'];
};

//in this file are functions for all sql queries
function checkTableExists($table) {

	global $LANG;
	global $dbh;
	global $db_server;
	$sql = "SELECT 1 FROM FROM INFORMATION_SCHEMA.TABLES where table_name = :table LIMIT 1";
	if ($db_server == 'pgsql') {
		// Use a nicer syntax
		$sql = 'SELECT 1 FROM pg_tables WHERE tablename = :table LIMIT 1';
	}

	$sth = $dbh->prepare($sql);
	
	if ($sth && $sth->execute(array(':table' => $table))) {
		if ($sth->fetch()) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function checkFieldExists($table,$field) {

	global $LANG;
	global $dbh;
	global $db_server;
	
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE column_name = :field AND table_name = :table LIMIT 1";
	if ($db_server == 'pgsql') {
		// Use a nicer syntax
		$sql = "SELECT 1 FROM pg_attribute a INNER JOIN pg_class c ON (a.attrelid = c.oid)  WHERE c.relkind = 'r' AND c.relname = :table AND a.attname = :field AND NOT a.attisdropped AND a.attnum > 0 LIMIT 1";
	}

	$sth = $dbh->prepare($sql);
	
	if ($sth && $sth->execute(array(':field' => $field, ':table' => $table))) {
		if ($sth->fetch()) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


function getURL()
{
	global $http_auth;
//	html2ps does not like &amp; and htmlcharacters encoding - latter useless since InvoiceID comes from an integer field	
//	$script = "/index.php?module=invoices&amp;view=templates/template&amp;invoice=".htmlspecialchars($invoiceID)."&amp;action=view&amp;location=pdf";
//	$script = "/index.php?module=invoices&view=templates/template&invoice=$invoiceID&action=view&location=pdf";
	$port = "";
	$dir = dirname($_SERVER['PHP_SELF']);

	//set the port of http(s) section
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
		$_SERVER['FULL_URL'] = "https://";
		//http://simpleinvoices.org/forum/topic-457.html
		//below code no longer required - please test if your using a non standard port
		/*
		if($_SERVER['SERVER_PORT']!="443") {
			$port .= "://" . $_SERVER[�SERVER_PORT�];
		}
		*/
	} else {
		$_SERVER['FULL_URL'] = "http://";
		//http://simpleinvoices.org/forum/topic-457.html
		//below code no longer required - please test if your using a non standard port
		/*
		if($_SERVER['SERVER_PORT']!="80") {
			$port = ":" . $_SERVER['SERVER_PORT'];
		}
		*/
	}

	//merge it all togehter
	if (isset($_SERVER['HTTP_HOST'])) {
		$_SERVER['FULL_URL'] .= $http_auth.$_SERVER['HTTP_HOST'].$dir.$script;
	} else {
		$_SERVER['FULL_URL'] .= $http_auth.$_SERVER['HTTP_HOST'].$dir.$script;
	}
	
	return $_SERVER['FULL_URL'];

}
function urlPDF($invoiceID) {
	
	$url = getURL();
//	html2ps does not like &amp; and htmlcharacters encoding - latter useless since InvoiceID comes from an integer field	
//	$script = "/index.php?module=invoices&amp;view=templates/template&amp;invoice=".htmlspecialchars($invoiceID)."&amp;action=view&amp;location=pdf";
	$script = "/index.php?module=invoices&view=templates/template&invoice=$invoiceID&action=view&location=pdf";

	$full_url=$url.$script;
	
	return $full_url;
}

function sql2array($strSql) { 
	global $dbh;
    $sqlInArray = null; 
 
    $result_strSql = dbQuery($strSql); 
 
    for($i=0;$sqlInRow = PDOStatement::fetchAll($result_strSql);$i++) { 
 
        $sqlInArray[$i] = $sqlInRow; 
    } 
    return $sqlInArray; 
}

/* Class: wrapper class for zend locale*/
class siLocal 
{
	/*Function: wrapper function for zend_locale_format::toNumber*/
	function number($number,$precision="",$locale="")
	{
		global $config;
		
		$locale == "" ? $locale = new Zend_Locale($config->local->locale) : $locale = $locale;
		$load_precision = $config->local->precision; 
		
		$precision == "" ? $precision = $load_precision : $precision = $precision;
		$formatted_number = Zend_Locale_Format::toNumber($number, array('precision' => $precision, 'locale' => $locale));
		return $formatted_number;
	}
	
	/*Function: wrapper function for zend_date*/
	function date($date,$length="",$locale="")
	{
		global $config;
		
		$locale == "" ? $locale = new Zend_Locale($config->local->locale) : $locale = $locale;
		$length == "" ? $length = "medium" : $lenght = $length;
		/*
		 * Length can be any of the Zend_Date lenghts - FULL, LONG, MEDIUM, SHORT
		 */

		$formatted_date = new Zend_Date($date,'yyyy-MM-dd');
		
		switch ($length) {
			case "full":
			    return $formatted_date->get(Zend_Date::DATE_FULL,$locale);
			    break;
			case "long":
			    return $formatted_date->get(Zend_Date::DATE_LONG,$locale);
			    break;
			case "medium":
			    return $formatted_date->get(Zend_Date::DATE_MEDIUM,$locale);
			    break;
			case "short":
			    return $formatted_date->get(Zend_Date::DATE_SHORT,$locale);
			    break;
			default:
				return $formatted_date->get(Zend_Date::DATE_SHORT,$locale);
		}
		
	}
	/*
	 * Function: number_formatted
	 * Description: wrapper for php number_format
	 * Purpose: to format numbers for data entry fields - ie invoice edit/ajax where data is in 6 decimial places but only neex x places in edit view
	 */
	function number_formatted($number)
	{
		global $config;
		$number_formatted = number_format($number, $config->local->precision, '.', '');
		return $number_formatted;
	}
}
?>
