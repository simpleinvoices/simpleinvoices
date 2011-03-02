<?php

if(LOGGING) {
	//Logging connection to prevent mysql_insert_id problems. Need to be called before the second connect...
	$log_dbh = db_connector();
}

$dbh = db_connector();

/*
 * TODO - remove this code - mysql 5 only 
if ($db_server == 'mysql') {
	//SC: May not really be 5...
	$mysql = 5;
}
*/


function db_connector() {

	global $config;
	/*
	* strip the pdo_ section from the adapter
	*/
	$pdoAdapter = substr($config->database->adapter, 4);
	
	if(!defined('PDO::MYSQL_ATTR_INIT_COMMAND') AND $pdoAdapter == "mysql" AND $config->database->adapter->utf8 == true)
	{ 
        simpleInvoicesError("PDO::mysql_attr");
	}

	try
	{
		
		switch ($pdoAdapter) 
		{

		    case "pgsql":
		    	$connlink = new PDO(
					$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
				);
		    	break;
		    	
		    case "sqlite":
		    	$connlink = new PDO(
					$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
				);
				break;
			
		    case "mysql":
                switch ($config->database->utf8)
                {
                    case true:
    
        			   	$connlink = new PDO(
        					'mysql:host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname, $config->database->params->username, $config->database->params->password,  array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
        				);
		        		break;
				
        		    case false:
		            default:
        		    	$connlink = new PDO(
        					$pdoAdapter.':host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
		        		);
    				break;
                }
    	    	break;

		}
		
		
	}
	catch( PDOException $exception )
	{
		simpleInvoicesError("dbConnection",$exception->getMessage());
		die($exception->getMessage());
	}
			
			
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
	/*
	// PDO Execution
	if($sth && $sth->execute()) {
		//dbLogger($sqlQuery);
	} else {
		echo "Dude, what happened to your query?:<br /><br /> ".htmlsafe($sqlQuery)."<br />".htmlsafe(end($sth->errorInfo()));
	// Earlier implementation did not return the $sth on error
	}
// $sth now has the PDO object or false on error.
	*/
	try {	
		$sth->execute();
	} catch(Exception $e){
		echo $e->getMessage();
		echo "dbQuery: Dude, what happened to your query?:<br /><br /> ".htmlsafe($sqlQuery)."<br />".htmlsafe(end($sth->errorInfo()));
	}
	
	return $sth;
	//$sth = null;
}

// Used for logging all queries
function dbLogger($sqlQuery) {
	global $log_dbh;
	global $dbh;
	global $auth_session;
	
	$userid = $auth_session->id;
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
	//echo $sql;
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
	global $auth_session;

	$print_customer = "SELECT * FROM ".TB_PREFIX."customers WHERE id = :id and domain_id = :domain_id";
	$sth = dbQuery($print_customer, ':id', $id, ':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getBiller($id) {
	global $LANG;
	global $dbh;
	global $auth_session;

	$print_biller = "SELECT * FROM ".TB_PREFIX."biller WHERE id = :id and domain_id = :domain_id";
	$sth = dbQuery($print_biller, ':id', $id, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	$biller = $sth->fetch();
	$biller['wording_for_enabled'] = $biller['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $biller;
}

function getPreference($id) {
	global $LANG;
	global $dbh;
	global $auth_session;

	$print_preferences = "SELECT * FROM ".TB_PREFIX."preferences WHERE pref_id = :id and domain_id = :domain_id";
	$sth = dbQuery($print_preferences, ':id', $id,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	$preference = $sth->fetch();
	$preference['status_wording'] = $preference['status']==1?$LANG['real']:$LANG['draft'];
	$preference['enabled'] = $preference['pref_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $preference;
}

function getSQLPatches() {
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."sql_patchmanager ORDER BY sql_release";                  
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetchAll();
}

function getPreferences() {
	global $LANG;
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences WHERE domain_id = :domain_id ORDER BY pref_description";
	$sth  = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
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
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_enabled != 0 and domain_id = :domain_id ORDER BY tax_description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_enabled ORDER BY tax_description";
	}
	$sth = dbQuery($sql, ':domain_id',$auth_session->domain_id ) or die(htmlsafe(end($dbh->errorInfo())));
	
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
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences WHERE pref_enabled and domain_id = :domain_id ORDER BY pref_description";
	$sth  = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

	return $sth->fetchAll();
}

function getCustomFieldLabels() {
	global $LANG;
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE domain_id = :domain_id ORDER BY cf_custom_field";
	$sth = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
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
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id ORDER BY name";
	$sth  = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
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
	global $auth_session;

	$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE enabled != 0 and domain_id = :domain_id ORDER BY name";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE enabled and domain_id = :domain_id ORDER BY name";
	}
	$sth = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
	return $sth->fetchAll();
}

function getTaxRate($id) {
	global $LANG;
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_id = :id and domain_id = :domain_id";
	$sth = dbQuery($sql, ':id', $id, ':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
	$tax = $sth->fetch();
	$tax['enabled'] = $tax['tax_enabled'] == 1 ? $LANG['enabled']:$LANG['disabled'];
	
	return $tax;
}
function getTaxTypes() {
	
	$types=  array(
                                '%' => '%',
                                '$' => '$'
	);
	return $types;
}

function getPaymentType($id) {
	global $LANG;
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_id = :id and domain_id = :domain_id";
	$sth = dbQuery($sql, ':id', $id, ':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	$paymentType = $sth->fetch();
	$paymentType['enabled'] = $paymentType['pt_enabled']==1?$LANG['enabled']:$LANG['disabled'];
	
	return $paymentType;
}

function getPayment($id) {
	global $config;
	global $dbh;

	$sql = "SELECT ap.*, c.id as customer_id, c.name AS customer, b.id as biller_id, b.name AS biller FROM ".TB_PREFIX."payment ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b WHERE ap.ac_inv_id = iv.id AND iv.customer_id = c.id AND iv.biller_id = b.id AND ap.id = :id";

	$sth = dbQuery($sql, ':id', $id) or die(htmlsafe(end($dbh->errorInfo())));
	$payment = $sth->fetch();
	$payment['date'] = siLocal::date($payment['ac_date']);
	return $payment;
}

function getInvoicePayments($id) {
	$sql = "SELECT 
				ap.*, 
				c.name as cname,
				b.name as bname 
			from 
				".TB_PREFIX."payment ap,
				".TB_PREFIX."invoices iv,
				".TB_PREFIX."customers c,
				".TB_PREFIX."biller b
			where 
				ap.ac_inv_id = iv.id 
				and 
				iv.customer_id = c.id 
				and 
				iv.biller_id = b.id 
				and 
				ap.ac_inv_id = :id 
			ORDER BY 
				ap.id DESC";
	return dbQuery($sql, ':id', $id);
}

function getCustomerPayments($id) {
	$sql = "SELECT 
				ap.*, 
				c.name as cname, 
				b.name as bname 
			from 
				".TB_PREFIX."payment ap, 
				".TB_PREFIX."invoices iv, 
				".TB_PREFIX."customers c, 
				".TB_PREFIX."biller b 
			where 
				ap.ac_inv_id = iv.id 
				and 
				iv.customer_id = c.id 
				and 
				iv.biller_id = b.id 
				and 
				c.id = :id 
			ORDER BY 
				ap.id DESC";
	return dbQuery($sql, ':id', $id);
}

function getPayments() {
	global $auth_session;
	
	$sql = "SELECT 
				ap.*, 
				c.name as cname, 
				b.name as bname 
			from 
				".TB_PREFIX."payment ap, 
				".TB_PREFIX."invoices iv, 
				".TB_PREFIX."customers c, 
				".TB_PREFIX."biller b 
			WHERE 
				ap.ac_inv_id = iv.id 
				AND 
				iv.customer_id = c.id 
				and 
				iv.biller_id = b.id 
				and 
				ap.domain_id = :domain_id
			ORDER BY ap.id DESC";
	
	return dbQuery($sql,':domain_id',$auth_session->domain_id);
}

function progressPayments($sth) {
	$payments = null;
	global $auth_session;

	for($i=0;$payment = $sth->fetch();$i++) {

		$sql = "SELECT pt_description FROM ".TB_PREFIX."payment_types WHERE pt_id = :id and domain_id = :domain_id";
		$tth = dbQuery($sql, ':id', $payment['ac_payment_type'], ':domain_id', $auth_session->domain_id);

		$pt = $tth->fetch();
		
		$payments[$i] = $payment;
		$payments[$i]['description'] = $pt['pt_description'];
		
	}
	
	return $payments;
}

function getPaymentTypes() {
	global $LANG;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE domain_id = :domain_id ORDER BY pt_description";
	$sth = dbQuery($sql, ':domain_id',$auth_session->domain_id);
	
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
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_enabled != 0 and domain_id = :domain_id ORDER BY pt_description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_enabled and domain_id = :domain_id ORDER BY pt_description";
	}
	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
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
	global $auth_session;

	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id and domain_id = :domain_id";
	$sth = dbQuery($sql, ':id', $id, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
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

	global $auth_session;
	/*if(isset($enabled)) {
		$enabled=$enabled;
	}*/
	
	if ($db_server == 'pgsql') {
		$sql = "INSERT into
			".TB_PREFIX."products
			(domain_id, description, unit_price, custom_field1, custom_field2,
			custom_field3, custom_field4, notes, enabled, visible)
		VALUES
			(	
				:domain_id, :description, :unit_price, :custom_field1,
				:custom_field2, :custom_field3, :custom_field4,
				:notes, :enabled, :visible
			)";
	} else {
		$sql = "INSERT into
			".TB_PREFIX."products
			(
				domain_id, description, unit_price, custom_field1, custom_field2,
				custom_field3, custom_field4, notes, enabled, visible
			)
		VALUES
			(	
				:domain_id,
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
		':domain_id',$auth_session->domain_id,	
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
	global $auth_session;
	
	(isset($_POST['enabled'])) ? $enabled = $_POST['enabled']  : $enabled = $enabled ;

	$sql = "INSERT into
		".TB_PREFIX."products
		(
			domain_id, 
            description, 
            unit_price, 
            cost,
            reorder_level,
            custom_field1, 
            custom_field2,
			custom_field3,
            custom_field4, 
            notes, 
            default_tax_id, 
            enabled, 
            visible
		)
	VALUES
		(	
			:domain_id,
			:description,
			:unit_price,
			:cost,
			:reorder_level,
			:custom_field1,
			:custom_field2,
			:custom_field3,
			:custom_field4,
			:notes,
			:default_tax_id,
			:enabled,
			:visible
		)";

	return dbQuery($sql,
		':domain_id',$auth_session->domain_id,	
		':description', $_POST['description'],
		':unit_price', $_POST['unit_price'],
		':cost', $_POST['cost'],
		':reorder_level', $_POST['reorder_level'],
		':custom_field1', $_POST['custom_field1'],
		':custom_field2', $_POST['custom_field2'],
		':custom_field3', $_POST['custom_field3'],
		':custom_field4', $_POST['custom_field4'],
		':notes', "".$_POST['notes'],
		':default_tax_id', $_POST['default_tax_id'],
		':enabled', $enabled,
		':visible', $visible
		);
}


function updateProduct() {
	
	$sql = "UPDATE ".TB_PREFIX."products
			SET
				description = :description,
				enabled = :enabled,
				default_tax_id = :default_tax_id,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				unit_price = :unit_price,
				cost = :cost,
				reorder_level = :reorder_level
			WHERE
				id = :id";

	return dbQuery($sql,
		':description', $_POST[description],
		':enabled', $_POST['enabled'],
		':notes', $_POST[notes],
		':default_tax_id', $_POST['default_tax_id'],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':unit_price', $_POST[unit_price],
		':cost', $_POST[cost],
		':reorder_level', $_POST[reorder_level],
		':id', $_GET[id]
		);
}

function getProducts() {
	global $LANG;
	global $dbh;
	global $db_server;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE visible = 1 AND domain_id = :domain_id ORDER BY description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."products WHERE visible and domain_id = :domain_id ORDER BY description";
	}
	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
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
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE enabled and domain_id = :domain_id ORDER BY description";
	$sth = dbQuery($sql, ':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
	return $sth->fetchAll();
}

function getTaxes() {
	global $LANG;
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE domain_id = :domain_id ORDER BY tax_description";
	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
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
	global $auth_session;
	
	$sql = "SELECT *,c.name AS name FROM ".TB_PREFIX."customers c, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'customer' AND c.id = s.value) AND c.domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultPaymentType() {
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."payment_types p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'payment_type' AND p.pt_id = s.value) AND p.domain_id = :domain_id";
	$sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultPreference() {
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."preferences p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'preference' AND p.pref_id = s.value) AND p.domain_id = :domain_id";
	$sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultBiller() {
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT *,b.name AS name FROM ".TB_PREFIX."biller b, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'biller' AND b.id = s.value ) and b.domain_id = :domain_id";
	$sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultTax() {
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."tax t, ".TB_PREFIX."system_defaults s WHERE (s.name = 'tax' AND t.tax_id = s.value) AND t.domain_id = :domain_id";
	$sth = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetch();
}

function getDefaultDelete() {
	global $LANG;
	global $dbh;
	global $auth_session;
	//domain id TODO
	
	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'delete')";
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	$array = $sth->fetch();
	$delete = $array['value']==1?$LANG['enabled']:$LANG['disabled'];
	return $delete;
}

function getDefaultLogging() {
	global $LANG;
	global $dbh;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'logging')";
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	$array = $sth->fetch();
	$delete = $array['value']==1?$LANG['enabled']:$LANG['disabled'];
	return $delete;
}

function getDefaultInventory() {
	global $LANG;
	global $dbh;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'inventory')";
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	$array = $sth->fetch();
	$delete = $array['value']==1?$LANG['enabled']:$LANG['disabled'];
	return $delete;
}

function getDefaultLanguage() {
	global $LANG;
	global $dbh;

	$sql = "SELECT value from ".TB_PREFIX."system_defaults s WHERE ( s.name = 'language')";
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	$entry = $sth->fetch();
	return $entry['value'];
}

function getInvoiceTotal($invoice_id) {
	global $LANG;
	
	$sql ="SELECT SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :invoice_id";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id);
	$res = $sth->fetch();
	//echo "TOTAL".$res['total'];
	return $res['total'];
}

function setInvoiceStatus($invoice, $status){
	global $dbh;

	$sql = "UPDATE " . TB_PREFIX . "invoices SET status_id =  :status WHERE id =  :id";
	$sth  = dbQuery($sql, ':status', $status, ':id', $invoice) or die(htmlsafe(end($dbh->errorInfo())));
}

function getInvoice($id) {
	global $dbh;
	global $config;
	global $auth_session; 
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE id =  :id and domain_id =  :domain_id";
	//echo $sql;
	
	$sth  = dbQuery($sql, ':id', $id, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

	//print_r($query);
	$invoice = $sth->fetch();
	
	//print_r($invoice);
	//exit();
	
	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['date'] = siLocal::date( $invoice['date'] );
	$invoice['total'] = getInvoiceTotal($invoice['id']);
	$invoice['gross'] = invoice::getInvoiceGross($invoice['id']);
	$invoice['paid'] = calc_invoice_paid($invoice['id']);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];

	
	#invoice total tax
	$sql ="SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :id";
	$sth = dbQuery($sql, ':id', $id) or die(htmlsafe(end($dbh->errorInfo())));
	$result = $sth->fetch();
	//$invoice['total'] = number_format($result['total'],2);
	$invoice['total_tax'] = $result['total_tax'];
		
	$invoice['tax_grouped'] = taxesGroupedForInvoice($id);

	return $invoice;
}


/*
Function: taxesGroupedForInvoice
Purpose: to show a nice summary of total $ for tax for an invoice
*/
function numberOfTaxesForInvoice($invoice_id)
{
	$sql = "select 
				DISTINCT tax.tax_id
			from 
				si_invoice_item_tax item_tax, 
				si_invoice_items item, 
				si_tax tax 
			where 
				item.id = item_tax.invoice_item_id 
				AND 
				tax.tax_id = item_tax.tax_id 
				AND 
				item.invoice_id = :invoice_id
				GROUP BY 
				tax.tax_id;";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id) or die(htmlsafe(end($dbh->errorInfo())));
	$result = $sth->rowCount();

	return $result;

}

/*
Function: taxesGroupedForInvoice
Purpose: to show a nice summary of total $ for tax for an invoice
*/
function taxesGroupedForInvoice($invoice_id)
{
	$sql = "select 
				tax.tax_description as tax_name, 
				sum(item_tax.tax_amount) as tax_amount,
				count(*) as count
			from 
				si_invoice_item_tax item_tax, 
				si_invoice_items item, 
				si_tax tax 
			where 
				item.id = item_tax.invoice_item_id 
				AND 
				tax.tax_id = item_tax.tax_id 
				AND 
				item.invoice_id = :invoice_id
				GROUP BY 
				tax.tax_id;";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id) or die(htmlsafe(end($dbh->errorInfo())));
	$result = $sth->fetchAll();

	return $result;

}

/*
Function: taxesGroupedForInvoiceItem
Purpose: to show a nice summary of total $ for tax for an invoice item - used for invoice editing
*/
function taxesGroupedForInvoiceItem($invoice_item_id)
{
	$sql = "select 
				item_tax.id as row_id, 
				tax.tax_description as tax_name, 
				tax.tax_id as tax_id 
			from 
				si_invoice_item_tax item_tax, 
				si_tax tax 
			where 
				item_tax.invoice_item_id = :invoice_item_id 
				AND 
				tax.tax_id = item_tax.tax_id 
				ORDER BY 
				row_id ASC;";
	$sth = dbQuery($sql, ':invoice_item_id', $invoice_item_id) or die(htmlsafe(end($dbh->errorInfo())));
	$result = $sth->fetchAll();

	return $result;

}

function setStatusExtension($extension_id, $status=2) {
	global $dbh;
	global $auth_session;

	//status=2 = toggle status
	if ($status == 2) {
		$sql = "SELECT enabled FROM ".TB_PREFIX."extensions WHERE id = :id AND domain_id = :domain_id LIMIT 1";
		$sth = dbQuery($sql,':id', $extension_id, ':domain_id', $auth_session->domain_id ) or die(htmlsafe(end($dbh->errorInfo())));
		$extension_info = $sth->fetch();
		$status = 1 - $extension_info['enabled'];
	}

	$sql = "UPDATE ".TB_PREFIX."extensions SET enabled =  :status WHERE id =  :id AND domain_id =  :domain_id LIMIT 1"; 
	if (dbQuery($sql, ':status', $status,':id', $extension_id, ':domain_id', $auth_session->domain_id)) {
		return true;
	}
	return false;
}

function getExtensionID($extension_name = "none") {

	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."extensions WHERE name LIKE  :extension_name AND (domain_id =  0 OR domain_id = :domain_id ) ORDER BY domain_id DESC LIMIT 1";
	$sth = dbQuery($sql,':extension_name', $extension_name, ':domain_id', $auth_session->domain_id ) or die(htmlsafe(end($dbh->errorInfo())));
	$extension_info = $sth->fetch();
	if (! $extension_info) { return -2; }			// -2 = no result set = extension not found
	if ($extension_info['enabled'] == 0) { return -1; }	// -1 = extension not enabled
	return $extension_info['id'];				//  0 = core, >0 is extension id
}

function getSystemDefaults() {

	global $dbh;
	global $auth_session;
    $db = new db();

    #get sql patch level - if less than 198 do sql with no exntesion table
    if ((checkTableExists(TB_PREFIX."system_defaults") == false))
    {
        return null;
    }
    if (getNumberOfDoneSQLPatches() < "198")
    {

        $sql_default  = "SELECT 
                                def.name,
                                def.value
                         FROM 
                            ".TB_PREFIX."system_defaults def";
        
        $sth = $db->query($sql_default) or die(htmlsafe(end($dbh->errorInfo())));	

    }
    if (getNumberOfDoneSQLPatches() >= "198")
    {
        $sql_default  = "SELECT 
                                def.name,
                                def.value
                         FROM 
                            ".TB_PREFIX."system_defaults def
                         INNER JOIN
                             ".TB_PREFIX."extensions ext ON (def.domain_id = ext.domain_id)";
        $sql_default .= " WHERE enabled=1";
        $sql_default .= " AND ext.name = 'core'";
        $sql_default .= " AND def.domain_id = :domain_id";
        $sql_default .= " ORDER BY extension_id ASC";		// order is important for overriding settings
        
        

        // get all settings from default domain (0)
        //$sth = dbQuery($sql.$current_settings.$order, 'domain_id', 0) or die(htmlsafe(end($dbh->errorInfo())));
        
        $sth = $db->query($sql_default, ':domain_id', 0) or die(htmlsafe(end($dbh->errorInfo())));	
	}

	$defaults = null;
	$default = null;
	
	
	while($default = $sth->fetch()) {
		$defaults["$default[name]"] = $default['value'];
	}

    if (getNumberOfDoneSQLPatches() > "198")
    {
        $sql  = "SELECT def.name,def.value FROM ".TB_PREFIX."system_defaults def INNER JOIN ".TB_PREFIX."extensions ext ON (def.extension_id = ext.id)";
        $sql .= " WHERE enabled=1";
        $sql .= " AND def.domain_id = :domain_id";
        $sql .= " ORDER BY extension_id ASC";		// order is important for overriding settings
        
        
        // add all settings from current domain
        //$sth = dbQuery($sql.$current_settings.$order, 'domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
        $sth = $db->query($sql, 'domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
        $default = null;

        while($default = $sth->fetch()) {
            $defaults["$default[name]"] = $default['value'];	// if setting is redefined, overwrite the previous value
        }
    }

	return $defaults;

}

function updateDefault($name,$value,$extension_name="core") {
	global $auth_session;
	
	$sql = "UPDATE ".TB_PREFIX."system_defaults SET value =  :value WHERE name = :name"; 

	$extension_id = getExtensionID($extension_name);
	if ($extension_id >= 0) { 
		$sql .= " AND extension_id = :extension_id"; 
	} else { 
		die(htmlsafe("Invalid extension name: ".$extension)); 
	}
	if (dbQuery($sql, ':value', $value, ':name', $name, ':extension_id', $extension_id)) { 
		return true; 
	}
	return false;
}

function getInvoiceType($id) {
	global $dbh;
	
	$sql = "SELECT * FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :id";
	$sth = dbQuery($sql, ':id', $id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetch();
}

function insertBiller() {
	global $db_server;
	global $auth_session;
		
	if ($db_server == 'pgsql') {
		$sql = "INSERT into
			".TB_PREFIX."biller (
				domain_id, name, street_address, street_address2, city,
				state, zip_code, country, phone, mobile_phone,
				fax, email, logo, footer, notes, custom_field1,
				custom_field2, custom_field3, custom_field4,
				enabled
			)
		VALUES
			(
				:domain_id, :name, :street_address, :street_address2, :city,
				:state, :zip_code, :country, :phone,
				:mobile_phone, :fax, :email, :logo, :footer,
				:notes, :custom_field1, :custom_field2,
				:custom_field3, :custom_field4, :enabled
			 )";
	} else {
		$sql = "INSERT into
			".TB_PREFIX."biller
			(
				id, domain_id, name, street_address, street_address2, city,
				state, zip_code, country, phone, mobile_phone,
				fax, email, logo, footer, paypal_business_name, 
				paypal_notify_url, paypal_return_url, eway_customer_id, notes, custom_field1,
				custom_field2, custom_field3, custom_field4,
				enabled

			)
		VALUES
			(
				NULL,
				:domain_id,
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
				:paypal_business_name,
				:paypal_notify_url,
				:paypal_return_url,
				:eway_customer_id,
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
		':paypal_business_name', $_POST[paypal_business_name],
		':paypal_notify_url', $_POST[paypal_notify_url],
		':paypal_return_url', $_POST[paypal_return_url],
		':eway_customer_id', $_POST[eway_customer_id],
		':notes', $_POST[notes],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':enabled', $_POST['enabled'],
		':domain_id', $auth_session->domain_id
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
				paypal_business_name = :paypal_business_name,
				paypal_notify_url = :paypal_notify_url,
				paypal_return_url = :paypal_return_url,
				eway_customer_id = :eway_customer_id,
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
		':paypal_business_name', $_POST[paypal_business_name],
		':paypal_notify_url', $_POST[paypal_notify_url],
		':paypal_return_url', $_POST[paypal_return_url],
		':eway_customer_id', $_POST[eway_customer_id],
		':notes', $_POST[notes],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':enabled', $_POST['enabled'],
		':id', $_GET[id]
		);
}

function updateCustomer() {
	global $db;
	global $config;

	//ugly hack with conditional sql - but couldn't get it working without this :(
	//TODO - make this just 1 query - refer svn history for info on past versions
	
    if($_POST['credit_card_number_new'] !='')
    {
        $credit_card_number = $_POST['credit_card_number_new'];
        
        //cc
        $enc = new encryption();
        $key = $config->encryption->default->key;	
        $encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);

        $cc_sql ="credit_card_number = :credit_card_number,";
        $cc_pdo_name = ":credit_card_number";
        $cc_pdo = $encrypted_credit_card_number;

        $sql = "UPDATE
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
                                credit_card_holder_name = :credit_card_holder_name,
                ".$cc_sql."
                                credit_card_expiry_month = :credit_card_expiry_month,
                                credit_card_expiry_year = :credit_card_expiry_year,
                                notes = :notes,
                                custom_field1 = :custom_field1,
                                custom_field2 = :custom_field2,
                                custom_field3 = :custom_field3,
                                custom_field4 = :custom_field4,
                                enabled = :enabled
                        WHERE
                                id = :id";

        return $db->query($sql,
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
        $cc_pdo_name, $cc_pdo ,
                ':credit_card_holder_name', $_POST[credit_card_holder_name],
                ':credit_card_expiry_month', $_POST[credit_card_expiry_month],
                ':credit_card_expiry_year', $_POST[credit_card_expiry_year],
                ':custom_field1', $_POST[custom_field1],
                ':custom_field2', $_POST[custom_field2],
                ':custom_field3', $_POST[custom_field3],
                ':custom_field4', $_POST[custom_field4],
                ':enabled', $_POST['enabled'],
                ':id', $_GET['id']
                );

    } else {


	$sql = "UPDATE
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
				credit_card_holder_name = :credit_card_holder_name,
				credit_card_expiry_month = :credit_card_expiry_month,
				credit_card_expiry_year = :credit_card_expiry_year,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				enabled = :enabled
			WHERE
				id = :id";


	return $db->query($sql,
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
		':credit_card_holder_name', $_POST[credit_card_holder_name],
		':credit_card_expiry_month', $_POST[credit_card_expiry_month],
		':credit_card_expiry_year', $_POST[credit_card_expiry_year],
		':custom_field1', $_POST[custom_field1],
		':custom_field2', $_POST[custom_field2],
		':custom_field3', $_POST[custom_field3],
		':custom_field4', $_POST[custom_field4],
		':enabled', $_POST['enabled'],
		':id', $_GET['id']
		);
	}
}

function insertCustomer() {
	global $db_server;
	global $auth_session;
    global $config;
	extract( $_POST );
	$sql = "INSERT INTO 
			".TB_PREFIX."customers
			(
				domain_id, attention, name, street_address, street_address2,
				city, state, zip_code, country, phone, mobile_phone,
				fax, email, notes,
				credit_card_holder_name, credit_card_number,
				credit_card_expiry_month, credit_card_expiry_year, 
				custom_field1, custom_field2,
				custom_field3, custom_field4, enabled
			)
			VALUES 
			(
				:domain_id ,:attention, :name, :street_address, :street_address2,
				:city, :state, :zip_code, :country, :phone, :mobile_phone,
				:fax, :email, :notes, 
				:credit_card_holder_name, :credit_card_number,
				:credit_card_expiry_month, :credit_card_expiry_year, 
				:custom_field1, :custom_field2,
				:custom_field3, :custom_field4, :enabled
			)";
	//cc
	$enc = new encryption();
    $key = $config->encryption->default->key;	
	$encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);

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
		':credit_card_holder_name', $credit_card_holder_name,
		':credit_card_number', $encrypted_credit_card_number,
		':credit_card_expiry_month', $credit_card_expiry_month,
		':credit_card_expiry_year', $credit_card_expiry_year,
		':custom_field1', $custom_field1,
		':custom_field2', $custom_field2,
		':custom_field3', $custom_field3,
		':custom_field4', $custom_field4,
		':enabled', $enabled,
		':domain_id',$auth_session->domain_id
		);
	
}

function searchCustomers($search) {
//TODO remove this function - note used anymore
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
	global $auth_session;

// tested for MySQL	
	$sql = "SELECT	
		i.id, 
		i.date, 
		i.type_id, 
		(SELECT sum( COALESCE(ii.total, 0)) FROM " . TB_PREFIX . "invoice_items ii where ii.invoice_id = i.id) As invd,
		(SELECT sum( COALESCE(ap.ac_amount, 0)) FROM " . TB_PREFIX . "payment ap where ap.ac_inv_id = i.id) As pmt,
		(SELECT COALESCE(invd, 0)) As total, 
		(SELECT COALESCE(pmt, 0)) As paid, 
		(select (total - paid)) as owing 
	FROM 
		" . TB_PREFIX . "invoices i 
	WHERE 
		i.customer_id = :id
		and
		i.domain_id = :domain_id
	ORDER BY 
		i.id DESC;";	

	$sth = dbQuery($sql, ':id', $id, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

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
	global $auth_session;
	
	$customer = null;
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
	$sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

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
	global $auth_session;
	
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled != 0 and domain_id = :domain_id ORDER BY name";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled and domain_id = :domain_id ORDER BY name";
	}
	$sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

	return $sth->fetchAll();
}

/* DELETE this function */
function getTopDebtor() {
  global $db_server;
  global $dbh;
  global $mysql;
  global $auth_session;

  $debtor = null;

  #Largest debtor query - start
  
	$sql = "SELECT	
	        	c.id as \"CID\",
	        	c.name as \"Customer\",
	        	sum(ii.total) as \"Total\",
	        	(select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Paid\",
	        	sum(ii.total) - (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Owing\"
	FROM
	        ".TB_PREFIX."customers c INNER JOIN
		".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	WHERE 
		c.domain_id = :domain_id
	GROUP BY
		\"CID\", iv.customer_id, c.id, c.name
	ORDER BY
	        \"Owing\" DESC
	LIMIT 1;
	";

	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));

	$debtor = $sth->fetch();
  
  #Largest debtor query - end
  return $debtor;
}

/* DELETE this function */
function getTopCustomer() {
  global $db_server;
  global $dbh;
  global $mysql;
  global $auth_session;

  $customer = null;

  #Top customer query - start
  
	$sql2 = "SELECT
			c.id as \"CID\",
	        	c.name as \"Customer\",
       			sum(ii.total) as \"Total\",
	        	(select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Paid\",
	        	sum(ii.total) - (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Owing\"

	FROM
       		".TB_PREFIX."customers c INNER JOIN
		".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	WHERE
		c.domain_id = :domain_id
	GROUP BY
	        \"CID\", iv.customer_id, \"Customer\"
	ORDER BY 
		\"Total\" DESC
	LIMIT 1;
";

	$tth = dbQuery($sql2,':domain_id',$auth_session->domain_id) or die(end($dbh->errorInfo()));

	$customer = $tth->fetch();
 
  #Top customer query - end
  return $customer;
}

/* DELETE this function */
function getTopBiller() {
  global $db_server;
  global $dbh;
  global $mysql;
  global $auth_session;

  $biller = null;

  #Top biller query - start
 	
	$sql3 = "SELECT
		b.name,  
		sum(ii.total) as Total 
	FROM 
		".TB_PREFIX."biller b INNER JOIN
		".TB_PREFIX."invoices iv ON (b.id = iv.biller_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	WHERE
		b.domain_id = :domain_id
	GROUP BY b.name
	ORDER BY Total DESC
	LIMIT 1;
	";

	$uth = dbQuery($sql3, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));

	$biller = $uth->fetch();
  
  #Top biller query - start
  return $biller;
}

function insertTaxRate() {
  	global $auth_session;
	global $LANG;

	$sql = "INSERT into ".TB_PREFIX."tax
				(domain_id, tax_description, tax_percentage, type,  tax_enabled)
			VALUES
				(:domain_id, :description, :percent, :type, :enabled)";
	
	$display_block = $LANG['save_tax_rate_success'];
	if (!(dbQuery($sql,
		':domain_id', $auth_session->domain_id,
		':description', $_POST['tax_description'],
		':percent', $_POST['tax_percentage'],
		':type', $_POST['type'],
		':enabled', $_POST['tax_enabled']))) {
		$display_block = $LANG['save_tax_rate_failure'];
	}
	return $display_block;
}

function updateTaxRate() {
	global $LANG;
	global $auth_session;
	
	$sql = "UPDATE
				".TB_PREFIX."tax
			SET
				tax_description = :description,
				tax_percentage = :percentage,
				type = :type,
				tax_enabled = :enabled
			WHERE
				tax_id = :id
			AND
				domain_id = :domain_id
			";

	$display_block = $LANG['save_tax_rate_success'];
	if (!(dbQuery($sql,
		':description', $_POST['tax_description'],
	  	':percentage', $_POST['tax_percentage'],
	  	':enabled', $_POST['tax_enabled'],
	  	':id', $_GET['id'],
	  	':domain_id', $auth_session->domain_id,
	  	':type', $_POST['type']

		))) {
		$display_block = $LANG['save_tax_rate_failure'];
	}
	return $display_block;
}

function insertInvoice($type) {
	global $dbh;
	global $db_server;
	global $auth_session;
	
	if ($db_server == 'mysql' && !_invoice_check_fk(
		$_POST['biller_id'], $_POST['customer_id'],
		$type, $_POST['preference_id'])) {
		return null;
	}
	$sql = "INSERT 
			INTO
		".TB_PREFIX."invoices (
			id, 
            		index_id,
			domain_id,
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
			:index_id,
			:domain_id,
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
				index_id,
				domain_id,
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
				:index_id,
				:domain_id,
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
    $pref_group=getPreference($_POST[preference_id]);

	$sth= dbQuery($sql,
		#':index_id', index::next('invoice',$pref_group[index_group],$_POST[biller_id]),
		':index_id', index::next('invoice',$pref_group[index_group]),
		':domain_id', $auth_session->domain_id,
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

    #index::increment('invoice',$pref_group[index_group],$_POST[biller_id]);
    index::increment('invoice',$pref_group[index_group]);

    return $sth;
}

function updateInvoice($invoice_id) {
	
    global $logger;

    $current_invoice = invoice::select($_POST['id']);
    $current_pref_group = getPreference($current_invoice[preference_id]);

    $new_pref_group=getPreference($_POST[preference_id]);

    $index_id = $current_invoice['index_id'];

//	$logger->log('Curent Index Group: '.$description, Zend_Log::INFO);
//	$logger->log('Description: '.$description, Zend_Log::INFO);

    if ($current_pref_group['index_group'] != $new_pref_group['index_group'])
    {
        $index_id = index::increment('invoice',$new_pref_group['index_group']);
    }

	if ($db_server == 'mysql' && !_invoice_check_fk(
		$_POST['biller_id'], $_POST['customer_id'],
		$type, $_POST['preference_id'])) {
		return null;
	}
	$sql = "UPDATE
			".TB_PREFIX."invoices
		SET
			index_id = :index_id,
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
        ':index_id', $index_id,
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

function insertInvoiceItem($invoice_id,$quantity,$product_id,$line_number,$line_item_tax_id,$description="", $unit_price="") {

	global $logger;
	global $LANG;
	//do taxes

	
	$tax_total = getTaxesPerLineItem($line_item_tax_id,$quantity, $unit_price);

	$logger->log(' ', Zend_Log::INFO);
	$logger->log(' ', Zend_Log::INFO);
	$logger->log('Invoice: '.$invoice_id.' Tax '.$line_item_tax_id.' for line item '.$line_number.': '.$tax_total, Zend_Log::INFO);
	$logger->log('Description: '.$description, Zend_Log::INFO);
	$logger->log(' ', Zend_Log::INFO);

	//line item gross total
	$gross_total = $unit_price  * $quantity;

	//line item total
	$total = $gross_total + $tax_total;	

	//Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
	if ($description == $LANG['description'])
	{	
		$description ="";
	}


	if ($db_server == 'mysql' && !_invoice_items_check_fk(
		$invoice_id, $product_id, $tax['tax_id'])) {
		return null;
	}
	$sql = "INSERT INTO ".TB_PREFIX."invoice_items 
			(
				invoice_id, 
				quantity, 
				product_id, 
				unit_price, 
				tax_amount, 
				gross_total, 
				description, 
				total
			) 
			VALUES 
			(
				:invoice_id, 
				:quantity, 
				:product_id, 
				:unit_price, 
				:tax_amount, 
				:gross_total, 
				:description, 
				:total
			)";

	//echo $sql;
	dbQuery($sql,
		':invoice_id', $invoice_id,
		':quantity', $quantity,
		':product_id', $product_id,
		':unit_price', $unit_price,
	//	':tax_id', $tax[tax_id],
	//	':tax_percentage', $tax[tax_percentage],
		':tax_amount', $tax_total,
		':gross_total', $gross_total,
		':description', $description,
		':total', $total
		);
	
	invoice_item_tax(lastInsertId(),$line_item_tax_id,$unit_price,$quantity,"insert");

	//TODO fix this
	return true;
}
/*
Function: getTaxesPerLineItem
Purpose: get the total tax for the line item
*/
function getTaxesPerLineItem($line_item_tax_id,$quantity, $unit_price)
{
	global $logger;

	foreach($line_item_tax_id as $key => $value) 
	{
		$logger->log("Key: ".$key." Value: ".$value, Zend_Log::INFO);
		$tax = getTaxRate($value);
		$logger->log('tax rate: '.$tax['tax_percentage'], Zend_Log::INFO);

		$tax_amount = lineItemTaxCalc($tax,$unit_price,$quantity);
		//get Total tax for line item
		$tax_total = $tax_total + $tax_amount;

		//$logger->log('Qty: '.$quantity.' Unit price: '.$unit_price, Zend_Log::INFO);
		//$logger->log('Tax rate: '.$tax[tax_percentage].' Tax type: '.$tax['tax_type'].' Tax $: '.$tax_amount, Zend_Log::INFO);

	}
	return $tax_total;
}

/*
Function: lineItemTaxCalc
Purpose: do the calc for the tax for tax x on line item y
*/
function lineItemTaxCalc($tax,$unit_price,$quantity)
{
	if($tax['type'] == "%")
	{
		$tax_amount = ( ($tax['tax_percentage'] / 100)  * $unit_price ) * $quantity;
	}
	if($tax['type'] == "$")
	{
		$tax_amount = $tax['tax_percentage'] * $quantity;
	}
		
	return $tax_amount;
}
/*
Function: invoice_item_tax
Purpose: insert/update the multiple taxes per line item into the si_invoice_item_tax table
*/
function invoice_item_tax($invoice_item_id,$line_item_tax_id,$unit_price,$quantity,$action="") {
	
	global $logger;

	//if editing invoice delete all tax info then insert first then do insert again
	//probably can be done without delete - someone to look into this if required - TODO
	if ($action =="update")
	{

		$sql_delete = "DELETE from
							".TB_PREFIX."invoice_item_tax
					   WHERE
							invoice_item_id = :invoice_item_id";
		$logger->log("Invoice item: ".$invoice_item_id." tax lines deleted", Zend_Log::INFO);

		dbQuery($sql_delete,':invoice_item_id',$invoice_item_id);


	}

	foreach($line_item_tax_id as $key => $value) 
	{
		if($value !== "")
		{
			$tax = getTaxRate($value);

			$logger->log("ITEM :: Key: ".$key." Value: ".$value, Zend_Log::INFO);
			$logger->log('ITEM :: tax rate: '.$tax['tax_percentage'], Zend_Log::INFO);

			$tax_amount = lineItemTaxCalc($tax,$unit_price,$quantity);
			//get Total tax for line item
			$tax_total = $tax_total + $tax_amount;

			$logger->log('ITEM :: Qty: '.$quantity.' Unit price: '.$unit_price, Zend_Log::INFO);
			$logger->log('ITEM :: Tax rate: '.$tax[tax_percentage].' Tax type: '.$tax['type'].' Tax $: '.$tax_amount, Zend_Log::INFO);

			$sql = "INSERT 
						INTO 
					".TB_PREFIX."invoice_item_tax 
					(
						invoice_item_id, 
						tax_id, 
						tax_type, 
						tax_rate, 
						tax_amount
					) 
					VALUES 
					(
						:invoice_item_id, 
						:tax_id,
						:tax_type,
						:tax_rate,
						:tax_amount
					)";

			dbQuery($sql,
				':invoice_item_id', $invoice_item_id,
				':tax_id', $tax[tax_id],
				':tax_type', $tax[type],
				':tax_rate', $tax[tax_percentage],
				':tax_amount', $tax_amount
				);
		}
	}
	//TODO fix this
	return true;
}
function updateInvoiceItem($id,$quantity,$product_id,$line_number,$line_item_tax_id,$description,$unit_price) {

	global $logger;
	global $LANG;
	//$product = getProduct($product_id);
	//$tax = getTaxRate($tax_id);
	
	$tax_total = getTaxesPerLineItem($line_item_tax_id,$quantity, $unit_price);

	$logger->log('Invoice: '.$invoice_id.' Tax '.$line_item_tax_id.' for line item '.$line_number.': '.$tax_total, Zend_Log::INFO);
	$logger->log('Description: '.$description, Zend_Log::INFO);
	$logger->log(' ', Zend_Log::INFO);

	//line item gross total
	$gross_total = $unit_price  * $quantity;

	//line item total
	$total = $gross_total + $tax_total;	

	//Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
	if ($description == $LANG['description'])
	{	
		$description ="";
	}


	if ($db_server == 'mysql' && !_invoice_items_check_fk(
		null, $product_id, $tax_id, 'update')) {
		return null;
	}

	$sql = "UPDATE ".TB_PREFIX."invoice_items 
	SET quantity =  :quantity,
	product_id = :product_id,
	unit_price = :unit_price,
	tax_amount = :tax_amount,
	gross_total = :gross_total,
	description = :description,
	total = :total			
	WHERE id = :id";
	
	//echo $sql;
		
	dbQuery($sql,
		':quantity', $quantity,
		':product_id', $product_id,
		':unit_price', $unit_price,
		':tax_amount', $tax_total,
		':gross_total', $gross_total,
		':description', $description,
		':total', $total,
		':id', $id
		);

	//if from a new invoice item in the edit page user lastInsertId()
	($id == null) ? $id = lastInsertId() : $id  =$id ;
	invoice_item_tax($id,$line_item_tax_id,$unit_price,$quantity,"update");

	return true;
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
	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
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
		<li><a href='".$tempentry[link]."'>".htmlsafe($tempentry[name])."</a>
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
//TODO remove this function - not used
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
//TODO remove this function - not used
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
	global $logger;
	global $auth_session; //TODO add some domain_id stuff in here if requried

	$lctable = strtolower($module);
	$s_idField = ''; // Presetting the whitelisted column to fail 

	/*
	 * SC: $valid_tables contains the base names of all tables that can
	 *     have rows deleted using this function.  This is used for
	 *     whitelisting deletion targets.
	 */
	$valid_tables = array('invoices', 'invoice_items', 'invoice_item_tax', 'products');

	if (in_array($lctable, $valid_tables)) {
		// A quick once-over on the dependencies of the possible tables
		if ($lctable == 'invoice_item_tax') 
        {
			// Not required by any FK relationships
			if (!in_array($idField, array('invoice_item_id'))) {
				// Fail, invalid identity field
				return false;
			} else {
				$s_idField = $idField;
			}
        } elseif ($lctable == 'invoice_items') {
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
				SELECT id FROM '.TB_PREFIX.'payment
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
    $logger->log("Item deleted: ".$sql, ZEND_Log::INFO);
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
function checkTableExists($table = "" ) {

	//$db = db::getInstance();
	//var_dump($db);
	$table == "" ? TB_PREFIX."biller" : $table;
	
  //  echo $table;
	global $LANG;
	global $dbh;
	global $config;
	switch ($config->database->adapter) 
	{

		case "pdo_pgsql":
			$sql = 'SELECT 1 FROM pg_tables WHERE tablename = '.$table.' LIMIT 1';
			break;

		case "pdo_sqlite":
			$sql = 'SELECT * FROM '.$table.'LIMIT 1';
			break;
		case "pdo_mysql":
		default:
		//mysql
			//$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES where table_name = :table LIMIT 1";
			$sql = "SHOW TABLES LIKE '".$table."'";
			break;
	}

	//$sth = $dbh->prepare($sql);
	$sth = dbQuery($sql);
	if ($sth->fetchAll())
	{
		return true;
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

function checkDataExists()
{
	$test = getNumberOfDoneSQLPatches();
	if ($test > 0 ){
		return true;
	} else {
		return false;
	}
}

function getURL()
{
	global $config;

	$port = "";
	$dir = dirname($_SERVER['PHP_SELF']);
	//remove incorrenct slashes for WinXP etc.
 $dir = str_replace('\\','',$dir);
 
	//set the port of http(s) section
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
		$_SERVER['FULL_URL'] = "https://";
	} else {
		$_SERVER['FULL_URL'] = "http://";

	}

	$_SERVER['FULL_URL'] .= $config->authentication->http.$_SERVER['HTTP_HOST'].$dir;

	return $_SERVER['FULL_URL'];

}
function urlPDF($invoiceID) {
	
	$url = getURL();
//	html2ps does not like &amp; and htmlcharacters encoding - latter useless since InvoiceID comes from an integer field	
//	$script = "/index.php?module=invoices&amp;view=templates/template&amp;invoice=".htmlsafe($invoiceID)."&amp;action=view&amp;location=pdf";
	$script = "/index.php?module=invoices&view=template&id=$invoiceID&action=view&location=pdf";

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


function getNumberOfDoneSQLPatches() {

	$check_patches_sql = "SELECT count(sql_patch) AS count FROM ".TB_PREFIX."sql_patchmanager ";
	$sth = dbQuery($check_patches_sql) or die(htmlsafe(end($dbh->errorInfo())));
		
	$patches = $sth->fetch();
	
	//Returns number of patches applied
	return $patches['count'];
}


function pdfThis($html,$file_location="",$pdfname)
{

	global $config;

//	set_include_path("../../../../library/pdf/");
	require_once('./library/pdf/config.inc.php');
	require_once('./library/pdf/pipeline.factory.class.php');
	require_once('./library/pdf/pipeline.class.php');
	parse_config_file('./library/pdf/html2ps.config');

	require_once("./include/init.php");	// for getInvoice() and getPreference()
	#$invoice_id = $_GET['id'];
	#$invoice = getInvoice($invoice_id);

	#$preference = getPreference($invoice['preference_id']);
	#$pdfname = trim($preference['pref_inv_wording']) . $invoice_id;

	#error_reporting(E_ALL);
	#ini_set("display_errors","1");
	#@set_time_limit(10000);

	/**
	 * Runs the HTML->PDF conversion with default settings
	 *
	 * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
	 * use absolute links (like http://my.host/image.gif).
	 *
	 * @param $path_to_html String path to source html file.
	 * @param $path_to_pdf  String path to file to save generated PDF to.
	 */
	if(!function_exists(convert_to_pdf))
	{
		function convert_to_pdf($html_to_pdf, $pdfname, $file_location="") {

			global $config;
		  
			$destination = $file_location=="download" ? "DestinationDownload" : "DestinationFile";
		  /**
		   * Handles the saving generated PDF to user-defined output file on server
		   */

		 if(!class_exists(MyFetcherLocalFile))
		 {
		  class MyFetcherLocalFile extends Fetcher {
			var $_content;

			function MyFetcherLocalFile($html_to_pdf) {
			  //$this->_content = file_get_contents($file);
			  $this->_content = $html_to_pdf;
			}

			function get_data($dummy1) {
			  return new FetchedDataURL($this->_content, array(), "");
			}

			function get_base_url() {
			  return "";
			}
		  }
		 }

		  $pipeline = PipelineFactory::create_default_pipeline("", // Attempt to auto-detect encoding
															   "");

		  // Override HTML source 
		  $pipeline->fetchers[] = new MyFetcherLocalFile($html_to_pdf);

		  $baseurl = "";
		  $media = Media::predefined($config->export->pdf->papersize);
		  $media->set_landscape(false);

		  global $g_config;
		  $g_config = array(
							'cssmedia'     => 'screen',
							'renderimages' => true,
							'renderlinks'  => true,
							'renderfields' => true,
							'renderforms'  => false,
							'mode'         => 'html',
							'encoding'     => '',
							'debugbox'     => false,
							'pdfversion'    => '1.4',

							'process_mode'     => 'single',
							//'output'     => 1,
							//'location'     => 'pdf',
							'pixels'     => $config->export->pdf->screensize,
							'media'     => $config->export->pdf->papersize,
				'margins'       => array(
							      'left'    => $config->export->pdf->leftmargin,
							      'right'   => $config->export->pdf->rightmargin,
							      'top'     => $config->export->pdf->topmargin,
							      'bottom'  => $config->export->pdf->bottommargin,
							      ),
							'transparency_workaround'     => 1,
							'imagequality_workaround'     => 1,

							'draw_page_border' => false
							);

			$media->set_margins($g_config['margins']);
			$media->set_pixels($config->export->pdf->screensize);

	/*
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 	// Date in the past

	header("Location: $myloc");
	*/
		  global $g_px_scale;
		  $g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels; 
		  global $g_pt_scale;
		  $g_pt_scale = $g_px_scale * 1.43; 

		  $pipeline->configure($g_config);
		  $pipeline->data_filters[] = new DataFilterUTF8("");
		  $pipeline->destination = new $destination($pdfname);
		  $pipeline->process($baseurl, $media);
		}
	}

	//echo "location: ".$file_location;
	convert_to_pdf($html, $pdfname, $file_location);

}
