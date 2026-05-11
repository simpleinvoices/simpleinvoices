<?php

require_once __DIR__ . '/gateway_secrets_crypto.php';

if(LOGGING) {
	//Logging connection to prevent mysql_insert_id problems. Need to be called before the second connect...
	$log_dbh = db_connector();
}

$dbh = db_connector();

// Cannot redfine LOGGING (withour PHP PECL runkit extension) since already true in define.php
// Ref: http://php.net/manual/en/function.runkit-method-redefine.php
// Hence take from system_defaults into new variable
// Initialise so that while it is being evaluated, it prevents logging
$can_log = false;
$can_chk_log = (LOGGING && (isset($auth_session->id) && $auth_session->id > 0) && getDefaultLoggingStatus());
$can_log = $can_chk_log;
unset($can_chk_log);

function db_connector() {

	global $config;
	/*
	* strip the pdo_ section from the adapter
	*/
	$pdoAdapter = substr($config->database->adapter, 4);

	if(!defined('PDO::MYSQL_ATTR_INIT_COMMAND') AND $pdoAdapter == "mysql" AND $config->database->utf8 == true)
	{
        simpleInvoicesError("PDO::mysql_attr");
	}

	try
	{

		switch ($pdoAdapter)
		{

		    case "pgsql":
		    	$port = !empty($config->database->params->port) ? ';port='.$config->database->params->port : '';
		    	$connlink = new PDO(
					'pgsql:host='.$config->database->params->host.$port.';dbname='.$config->database->params->dbname,
					$config->database->params->username,
					$config->database->params->password
				);
				$connlink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    	break;

		    case "sqlite":
		    	$dsn = $config->database->params->dbname;
		    	if ($dsn !== ':memory:' && (strlen($dsn) === 0 || $dsn[0] !== '/')) {
		    		$base = preg_replace('/\.sqlite$/', '', $dsn);
		    		$dir  = realpath('.') . '/databases/sqlite';
		    		if (!is_dir($dir)) {
		    			mkdir($dir, 0755, true);
		    		}
		    		$dsn = $dir . '/' . $base . '.sqlite';
		    	}
		    	$connlink = new PDO('sqlite:' . $dsn);
				$connlink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connlink->exec('PRAGMA journal_mode=WAL');
				$connlink->exec('PRAGMA foreign_keys=ON');
				break;

		    case "mysql":
                switch ($config->database->utf8)
                {
                    case true:

        			   	$connlink = new PDO(
        					'mysql:host='.$config->database->params->host.';port='.$config->database->params->port.';dbname='.$config->database->params->dbname, $config->database->params->username, $config->database->params->password,  array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
        				);
        				$connlink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		        		break;

        		    case false:
		            default:
        		    	$connlink = new PDO(
        					'mysql:host='.$config->database->params->host.';port='.$config->database->params->port.';dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
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
		dbLogger($sqlQuery);
	} catch(Exception $e){
		echo $e->getMessage();
		$errInfo = ($sth instanceof PDOStatement) ? $sth->errorInfo() : [];
		$errTail = is_array($errInfo) && $errInfo !== [] ? (string) end($errInfo) : '';
		echo "dbQuery: Dude, what happened to your query?:<br /><br /> ".htmlsafe($sqlQuery)."<br />".htmlsafe($errTail);
	}

	return $sth;
	//$sth = null;
}

// Used for logging all queries
function dbLogger($sqlQuery) {
// For PDO it gives only the skeleton sql before merging with data

	global $log_dbh;
	global $dbh;
	global $auth_session;
	global $can_log;

	$userid = $auth_session->id;
	if($can_log
		&& (preg_match('/^\s*select/iD',$sqlQuery) == 0)
		&& (preg_match('/^\s*show\s*tables\s*like/iD',$sqlQuery) == 0)
	   ) {
		// Only log queries that could result in data/database  modification

		$last = null;
		$tth = null;
		$sql = "INSERT INTO ".TB_PREFIX."log (domain_id, timestamp, userid, sqlquerie, last_id) VALUES (?, CURRENT_TIMESTAMP , ?, ?, ?)";

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
		$tth->execute(array($auth_session->domain_id, $userid, trim($sqlQuery), $last));
		unset($tth);
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
		$sth = $dbh->prepare('SELECT lastval()');
		$sth->execute();
		return $sth->fetchColumn();
	} elseif ($pdoAdapter == 'sqlite') {
		return $dbh->lastInsertId();
	} else {
		// MySQL
		$sth = $dbh->prepare('SELECT last_insert_id()');
		$sth->execute();
		return $sth->fetchColumn();
	}
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
	$domain_id = domain_id::get();

	//Check biller
	$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'biller WHERE id = :id AND domain_id = :domain_id');
	$sth->execute(array(':id' => $biller, ':domain_id' => $domain_id));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check customer
	$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'customers WHERE id = :id AND domain_id = :domain_id');
	$sth->execute(array(':id' => $customer, ':domain_id' => $domain_id));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check invoice type
	$sth = $dbh->prepare('SELECT count(inv_ty_id) FROM '.TB_PREFIX.'invoice_type WHERE inv_ty_id = :id');
	$sth->execute(array(':id' => $type));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check preferences
	$sth = $dbh->prepare('SELECT count(pref_id) FROM '.TB_PREFIX.'preferences WHERE pref_id = :id AND domain_id = :domain_id');
	$sth->execute(array(':id' => $preference, ':domain_id' => $domain_id));
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
	$domain_id = domain_id::get();

	//Check invoice
	if (is_null($update) || !is_null($invoice)) {
		$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'invoices WHERE id = :id AND domain_id = :domain_id');
		$sth->execute(array(':id' => $invoice, ':domain_id' => $domain_id));
		if ($sth->fetchColumn() == 0) { return false; }
	}
	//Check product
	$sth = $dbh->prepare('SELECT count(id) FROM '.TB_PREFIX.'products WHERE id = :id AND domain_id = :domain_id');
	$sth->execute(array(':id' => $product, ':domain_id' => $domain_id));
	if ($sth->fetchColumn() == 0) { return false; }
	//Check tax id (line items may legally have no per-line tax)
	if ($tax !== null && $tax !== '') {
		$sth = $dbh->prepare('SELECT count(tax_id) FROM '.TB_PREFIX.'tax WHERE tax_id = :id AND domain_id = :domain_id');
		$sth->execute(array(':id' => $tax, ':domain_id' => $domain_id));
		if ($sth->fetchColumn() == 0) { return false; }
	}

	//All good
	return true;
}

function getGenericRecord($table, $id, $domain_id='', $id_field='id') {

	$domain_id = domain_id::get($domain_id);

	$record_sql = "SELECT * FROM ".TB_PREFIX."$table WHERE $id_field = :id AND domain_id = :domain_id";
	$sth = dbQuery($record_sql, ':id', $id, ':domain_id',$domain_id);
	return $sth->fetch();
}

function getCustomer($id, $domain_id='') {
	return getGenericRecord('customers', $id, $domain_id);
}

function getBiller($id, $domain_id='') {
	global $LANG;
	$record = getGenericRecord('biller', $id, $domain_id);
	if (!$record) return false;
	$record = si_biller_row_decrypt_gateway_secrets($record);
	$record['wording_for_enabled'] = $record['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $record;
}

function getPreference($id, $domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);
	$record = getGenericRecord('preferences', $id, $domain_id, 'pref_id');
	if (!$record) return false;
	$record['status_wording'] = $record['status']==1?$LANG['real']:$LANG['draft'];
	$record['enabled'] = $record['pref_enabled']==1?$LANG['enabled']:$LANG['disabled'];

	// Resolve currency_code/position from si_currency via currency_id
	if (!empty($record['currency_id'])) {
		require_once __DIR__ . '/class/siCurrencies.php';
		$currRow = siCurrencies::getById((int) $record['currency_id'], $domain_id);
		if ($currRow) {
			$record['currency_code'] = $currRow['currency_code'] ?? '';
			$record['currency_position'] = $currRow['currency_position'] ?? 'left';
		}
	}

	return $record;
}

function getPaymentTerms($domain_id='') {
	$domain_id = domain_id::get($domain_id);
	$sql = "SELECT * FROM ".TB_PREFIX."payment_terms WHERE domain_id = :domain_id ORDER BY sort_order ASC, term_id ASC";
	$sth = dbQuery($sql, ':domain_id', $domain_id);
	return $sth->fetchAll();
}

function getPaymentTerm($id, $domain_id='') {
	if ($id === null || $id === '' || (int)$id <= 0) {
		return false;
	}
	$domain_id = domain_id::get($domain_id);
	$sql = "SELECT * FROM ".TB_PREFIX."payment_terms WHERE term_id = :id AND domain_id = :domain_id";
	$sth = dbQuery($sql, ':id', (int)$id, ':domain_id', $domain_id);
	$row = $sth->fetch();
	return $row ?: false;
}

/**
 * @return list<string>
 */
function getPaymentTermCalcKindCodes(): array {
	return ['NET_DAYS', 'EOM', 'EOM_PLUS_DAYS', 'MFI_DAY'];
}

function paymentTermCodeExists(string $code, ?int $excludeTermId = null, $domain_id=''): bool {
	$domain_id = domain_id::get($domain_id);
	$sql = "SELECT 1 FROM ".TB_PREFIX."payment_terms WHERE term_code = :code AND domain_id = :domain_id";
	if ($excludeTermId !== null && $excludeTermId > 0) {
		$sql .= " AND term_id <> :tid";
		$sth = dbQuery($sql, ':code', $code, ':domain_id', $domain_id, ':tid', $excludeTermId);
	} else {
		$sth = dbQuery($sql, ':code', $code, ':domain_id', $domain_id);
	}
	return (bool) $sth->fetch();
}

/**
 * @param array{domain_id:int,term_code:string,term_label:string,calc_kind:string,param_int:int|null,sort_order:int} $row
 */
function insertPaymentTerm(array $row): bool {
	$code = $row['term_code'];
	$label = $row['term_label'];
	$kind = $row['calc_kind'];
	$param = $row['param_int'];
	$sort = (int) $row['sort_order'];
	$domain_id = domain_id::get($row['domain_id'] ?? '');

	$sql = "INSERT INTO ".TB_PREFIX."payment_terms (domain_id, term_code, term_label, calc_kind, param_int, sort_order)"
		." VALUES (:domain_id, :code, :label, :kind, :param, :sort)";
	return (bool) dbQuery($sql,
		':domain_id', $domain_id,
		':code', $code,
		':label', $label,
		':kind', $kind,
		':param', $param,
		':sort', $sort
	);
}

/**
 * @param array{term_code:string,term_label:string,calc_kind:string,param_int:int|null,sort_order:int} $row
 */
function updatePaymentTerm(int $termId, array $row, $domain_id=''): bool {
	$domain_id = domain_id::get($domain_id);
	$sql = "UPDATE ".TB_PREFIX."payment_terms SET"
		." term_code = :code,"
		." term_label = :label,"
		." calc_kind = :kind,"
		." param_int = :param,"
		." sort_order = :sort"
		." WHERE term_id = :id AND domain_id = :domain_id";
	return (bool) dbQuery($sql,
		':code', $row['term_code'],
		':label', $row['term_label'],
		':kind', $row['calc_kind'],
		':param', $row['param_int'],
		':sort', (int) $row['sort_order'],
		':id', $termId,
		':domain_id', $domain_id
	);
}

function deletePaymentTerm(int $termId, $domain_id=''): bool {
	if ($termId <= 0) {
		return false;
	}
	$domain_id = domain_id::get($domain_id);
	dbQuery(
		"UPDATE ".TB_PREFIX."preferences SET payment_term_id = NULL WHERE payment_term_id = :id AND domain_id = :domain_id",
		':id', $termId,
		':domain_id', $domain_id
	);
	dbQuery(
		"UPDATE ".TB_PREFIX."invoices SET payment_term_id = NULL WHERE payment_term_id = :id AND domain_id = :domain_id",
		':id', $termId,
		':domain_id', $domain_id
	);
	$sql = "DELETE FROM ".TB_PREFIX."payment_terms WHERE term_id = :id AND domain_id = :domain_id";
	return (bool) dbQuery($sql, ':id', $termId, ':domain_id', $domain_id);
}

function getTaxRate($id, $domain_id='') {
	global $LANG;
	$record = getGenericRecord('tax', $id, $domain_id, 'tax_id');
	if (!$record) return false;
	$record['enabled'] = $record['tax_enabled'] == 1 ? $LANG['enabled']:$LANG['disabled'];
	return $record;
}

function getSQLPatches() {

	$sql  = "SELECT * FROM ".TB_PREFIX."sql_patchmanager
	            WHERE NOT (sql_patch = '' AND sql_release='' AND sql_statement = '')
	            ORDER BY CAST(sql_patch_ref AS UNSIGNED) DESC";
	$sth = dbQuery($sql);
	return $sth->fetchAll();
}

function getPreferences($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."preferences WHERE domain_id = :domain_id ORDER BY pref_description";
	$sth  = dbQuery($sql,':domain_id', $domain_id);

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

function getActiveTaxes($domain_id='') {
	global $LANG;
	global $db_server;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_enabled != 0 and domain_id = :domain_id ORDER BY tax_description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE tax_enabled AND domain_id = :domain_id ORDER BY tax_description";
	}
	$sth = dbQuery($sql, ':domain_id',$domain_id);

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

function getActivePreferences($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."preferences WHERE pref_enabled and domain_id = :domain_id ORDER BY pref_description";
	$sth  = dbQuery($sql, ':domain_id', $domain_id);

	return $sth->fetchAll();
}

function getCustomFieldLabels($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE domain_id = :domain_id ORDER BY cf_custom_field";
	$sth = dbQuery($sql,':domain_id',$domain_id);

	for($i=0;$customField = $sth->fetch();$i++) {
		$customFields[$customField['cf_custom_field']] = $customField['cf_custom_label'];

		if($customFields[$customField['cf_custom_field']] == null) {
			//If not set, don't show...
			$customFields[$customField['cf_custom_field']] = $LANG["custom_field"].' '.($i%4+1);
		}
	}

	return $customFields;
}

function getBillers($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id ORDER BY name";
	$sth  = dbQuery($sql,':domain_id',$domain_id);

	$billers = null;

	for($i=0; $biller = $sth->fetch(); $i++) {

		$biller = si_biller_row_decrypt_gateway_secrets($biller);
  		if ($biller['enabled'] == 1) {
  			$biller['enabled'] = $LANG['enabled'];
  		} else {
  			$biller['enabled'] = $LANG['disabled'];
  		}
		$billers[$i] = $biller;
	}

	return $billers;
}

function getActiveBillers($domain_id='') {
	global $db_server;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE enabled != 0 AND domain_id = :domain_id ORDER BY name";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE enabled AND domain_id = :domain_id ORDER BY name";
	}
	$sth = dbQuery($sql,':domain_id',$domain_id);

	$rows = $sth->fetchAll();
	foreach ($rows as $i => $row) {
		$rows[$i] = si_biller_row_decrypt_gateway_secrets($row);
	}

	return $rows;
}

function getTaxTypes() {

	$types = array(
                    '%' => '%',
                    '$' => '$'
	);
	return $types;
}

function getPaymentType($id, $domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_id = :id AND domain_id = :domain_id";
	$sth = dbQuery($sql, ':id', $id, ':domain_id',$domain_id);
	$paymentType = $sth->fetch();
	$paymentType['enabled'] = $paymentType['pt_enabled']==1?$LANG['enabled']:$LANG['disabled'];

	return $paymentType;
}

function getPayment($id, $domain_id='') {
	global $config;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT
		ap.*,
		c.id as customer_id,
		c.name AS customer,
		b.id as biller_id,
		b.name AS biller
	FROM ".TB_PREFIX."payment ap,
		 ".TB_PREFIX."invoices iv,
		 ".TB_PREFIX."customers c,
		 ".TB_PREFIX."biller b
	WHERE
		ap.ac_inv_id = iv.id
	AND iv.customer_id = c.id
	AND iv.biller_id = b.id
	AND ap.id = :id
	AND ap.domain_id = :domain_id";

	$sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
	$payment = $sth->fetch();
	$payment['date'] = siLocal::date($payment['ac_date']);
	return $payment;
}

function getInvoicePayments($id, $domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT
				ap.*,
				c.name AS cname,
				b.name AS bname
			FROM
				".TB_PREFIX."payment ap,
				".TB_PREFIX."invoices iv,
				".TB_PREFIX."customers c,
				".TB_PREFIX."biller b
			WHERE
				ap.ac_inv_id = :id
			AND ap.domain_id = :domain_id
			AND ap.ac_inv_id = iv.id
			AND iv.domain_id = ap.domain_id
			AND iv.customer_id = c.id
			AND c.domain_id = iv.domain_id
			AND iv.biller_id = b.id
			AND b.domain_id = iv.domain_id
			ORDER BY
				ap.id DESC";

	return dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
}

function getCustomerPayments($id, $domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT
				ap.*,
				c.name AS cname,
				b.name AS bname
			FROM
				".TB_PREFIX."payment ap,
				".TB_PREFIX."invoices iv,
				".TB_PREFIX."customers c,
				".TB_PREFIX."biller b
			WHERE
				c.id = :id
			AND ap.domain_id = :domain_id
			AND ap.ac_inv_id = iv.id
			AND iv.domain_id = ap.domain_id
			AND iv.customer_id = c.id
			AND c.domain_id = iv.domain_id
			AND iv.biller_id = b.id
			AND b.domain_id = iv.domain_id
			ORDER BY
				ap.id DESC";

	return dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
}

function getPayments($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT
				ap.*,
				c.name AS cname,
				b.name AS bname
			FROM 
				".TB_PREFIX."payment ap,
				".TB_PREFIX."invoices iv,
				".TB_PREFIX."customers c,
				".TB_PREFIX."biller b
			WHERE
				ap.domain_id = :domain_id
			AND ap.ac_inv_id = iv.id
			AND iv.domain_id = ap.domain_id
			AND iv.customer_id = c.id
			AND c.domain_id = iv.domain_id
			AND iv.biller_id = b.id
			AND b.domain_id = iv.domain_id
			ORDER BY
				ap.id DESC";

	return dbQuery($sql,':domain_id',$domain_id);
}

function progressPayments($sth, $domain_id='') {

	$domain_id = domain_id::get($domain_id);
	$payments = null;

	for($i=0;$payment = $sth->fetch();$i++) {

		$sql = "SELECT pt_description FROM ".TB_PREFIX."payment_types WHERE pt_id = :id and domain_id = :domain_id";
		$tth = dbQuery($sql, ':id', $payment['ac_payment_type'], ':domain_id', $domain_id);

		$pt = $tth->fetch();

		$payments[$i] = $payment;
		$payments[$i]['description'] = $pt['pt_description'];

	}

	return $payments;
}

function getPaymentTypes($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE domain_id = :domain_id ORDER BY pt_description";
	$sth = dbQuery($sql, ':domain_id',$domain_id);

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

function getActivePaymentTypes($domain_id='') {
	global $LANG;
	global $db_server;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_enabled != 0 and domain_id = :domain_id ORDER BY pt_description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."payment_types WHERE pt_enabled and domain_id = :domain_id ORDER BY pt_description";
	}
	$sth = dbQuery($sql, ':domain_id', $domain_id);

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

function getProduct($id, $domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id and domain_id = :domain_id";
	$sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
	$product = $sth->fetch();
	if (!$product) return false;
	$product['wording_for_enabled'] = $product['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $product;
}

/*function insertProduct($description,$unit_price,$enabled=1,$visible=1,$notes="",$custom_field1="",$custom_field2="",$custom_field3="",$custom_field4="") {
	$sql = "INSERT INTO ".TB_PREFIX."products
		(`description`,`unit_price`,`notes`,`enabled`,`visible`,`custom_field1`,`custom_field2`,`custom_field3`,`custom_field4`) 
		VALUES('$description','$unit_price','$notes',$enabled,$visible,'$custom_field1','$custom_field2','$custom_field3','$custom_field4');";
	
	return mysqlQuery($sql);
}*/

function insertProductComplete($description, $unit_price, $custom_field2, $custom_field3, $custom_field4, $notes, $enabled = 1, $visible = 1, $custom_field1 = null, $domain_id = '') {

	$domain_id = domain_id::get($domain_id);

	$sql = "INSERT into
    ".TB_PREFIX."products (
            domain_id,
            description,
            unit_price,
            custom_field1,
            custom_field2,
            custom_field3,
            custom_field4,
            notes,
            enabled,
            visible
    ) VALUES (
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

	return dbQuery($sql,
		':domain_id',$domain_id,
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

function insertProduct($enabled=1,$visible=1, $domain_id='') {
    global $logger;
	$domain_id = domain_id::get($domain_id);
	$descTrim = trim((string) ($_POST['description'] ?? ''));
	if ($descTrim !== '' && productDescriptionExists($descTrim, null, $domain_id)) {
		return false;
	}

	if (isset($_POST['enabled'])) $enabled = $_POST['enabled'];
    //select all attributes
    $sql = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE domain_id = :domain_id";
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    $attributes = $sth->fetchAll();

	$logger->log('Attr: '.var_export($attributes,true), LegacyLogger::INFO);
    $attr = array();
    foreach($attributes as $k=>$v)
    {
    	$logger->log('Attr key: '.$k, LegacyLogger::INFO);
    	$logger->log('Attr value: '.var_export($v,true), LegacyLogger::INFO);
    	$logger->log('Attr set value: '.$k, LegacyLogger::INFO);
        if($_POST['attribute'.$v['id']] == 'true')
        {
            //$attr[$k]['attr_id'] = $v['id'];
            $attr[$v['id']] = $_POST['attribute'.$v['id']];
//            $attr[$k]['a$v['id']] = $_POST['attribute'.$v['id']];
        }

    }
	$logger->log('Attr array: '.var_export($attr,true), LegacyLogger::INFO);
	$notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
    $show_description =  ($_POST['show_description'] == 'true' ? 'Y' : NULL) ;
    $insert_default_tax_id = ($_POST['default_tax_id'] !== '' ? $_POST['default_tax_id'] : NULL);

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
            visible,
            attribute,
            notes_as_description,
            show_description
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
			:visible,
            :attribute,
            :notes_as_description,
            :show_description
		)";

	return dbQuery($sql,
		':domain_id',$domain_id,
		':description', $_POST['description'],
		':unit_price', $_POST['unit_price'],
		':cost', $_POST['cost'],
		':reorder_level', $_POST['reorder_level'],
		':custom_field1', $_POST['custom_field1'],
		':custom_field2', $_POST['custom_field2'],
		':custom_field3', $_POST['custom_field3'],
		':custom_field4', $_POST['custom_field4'],
		':notes', "".$_POST['notes'],
		':default_tax_id', $insert_default_tax_id,
		':enabled', $enabled,
		':visible', $visible,
		':attribute', json_encode($attr),
		':notes_as_description', $notes_as_description,
		':show_description', $show_description
		);
}


function updateProduct($domain_id='') {

	$domain_id = domain_id::get($domain_id);
	$descTrim = trim((string) ($_POST['description'] ?? ''));
	$editId = (int) ($_GET['id'] ?? 0);
	if ($descTrim !== '' && $editId > 0 && productDescriptionExists($descTrim, $editId, $domain_id)) {
		return false;
	}

    //select all attributes
    $sql = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE domain_id = :domain_id";
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    $attributes = $sth->fetchAll();

    $attr = array();
    foreach($attributes as $k=>$v)
    {
        if($_POST['attribute'.$v['id']] == 'true')
        {
            $attr[$v['id']] = $_POST['attribute'.$v['id']];
        }

    }
	$notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
    $show_description =  ($_POST['show_description'] == 'true' ? 'Y' : NULL) ;
    $update_default_tax_id = ($_POST['default_tax_id'] !== '' ? $_POST['default_tax_id'] : NULL);

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
				reorder_level = :reorder_level,
                attribute = :attribute,
                notes_as_description = :notes_as_description,
                show_description = :show_description
			WHERE
				id = :id
			AND domain_id = :domain_id";

	return dbQuery($sql,
		':domain_id',$domain_id,
		':description', $_POST['description'],
		':enabled', $_POST['enabled'],
		':notes', $_POST['notes'],
		':default_tax_id', $update_default_tax_id,
		':custom_field1', $_POST['custom_field1'],
		':custom_field2', $_POST['custom_field2'],
		':custom_field3', $_POST['custom_field3'],
		':custom_field4', $_POST['custom_field4'],
		':unit_price', $_POST['unit_price'],
		':cost', $_POST['cost'],
		':reorder_level', $_POST['reorder_level'],
		':attribute', json_encode($attr),
		':notes_as_description', $notes_as_description,
		':show_description', $show_description,
		':id', $_GET['id']
		);
}

function getProducts($domain_id='') {
	global $LANG;
	global $db_server;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE visible = 1 AND domain_id = :domain_id ORDER BY description";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."products WHERE visible and domain_id = :domain_id ORDER BY description";
	}
	$sth = dbQuery($sql, ':domain_id', $domain_id);

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

function getActiveProducts($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."products WHERE enabled AND domain_id = :domain_id ORDER BY description";
	$sth = dbQuery($sql, ':domain_id',$domain_id);

	return $sth->fetchAll();
}

function getTaxes($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."tax WHERE domain_id = :domain_id ORDER BY tax_description";
	$sth = dbQuery($sql, ':domain_id', $domain_id);

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

function getDefaultGeneric($param, $bool=true, $domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	if (checkTableExists(TB_PREFIX."system_defaults") == false) {
		return $bool ? ($LANG['disabled'] ?? 'disabled') : null;
	}

	$sql = "SELECT value FROM ".TB_PREFIX."system_defaults s WHERE ( s.name = :param AND s.domain_id = :domain_id)";
	$sth = dbQuery($sql, ':param', $param, ':domain_id', $domain_id);
	if (!$sth) {
		return $bool ? ($LANG['disabled'] ?? 'disabled') : null;
	}
	$array = $sth->fetch();
	if (!$array || !array_key_exists('value', $array)) {
		return $bool ? ($LANG['disabled'] ?? 'disabled') : null;
	}
	$paramval = (($bool) ? ($array['value']==1?$LANG['enabled']:$LANG['disabled']) : $array['value']);
	return $paramval;
}

function getDefaultCustomer($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT c.name AS name FROM ".TB_PREFIX."customers c, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'customer' AND c.id = s.value AND c.domain_id = s.domain_id AND s.domain_id = :domain_id)";
	$sth = dbQuery($sql, ':domain_id', $domain_id);
	return $sth->fetch();
}

function getDefaultPaymentType($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT p.pt_description AS pt_description FROM ".TB_PREFIX."payment_types p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'payment_type' AND p.pt_id = s.value AND p.domain_id = s.domain_id AND s.domain_id = :domain_id)";
	$sth = dbQuery($sql,':domain_id', $domain_id);
	return $sth->fetch();
}

function getDefaultPreference($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."preferences p, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'preference' AND p.pref_id = s.value AND p.domain_id = s.domain_id AND s.domain_id = :domain_id)";
	$sth = dbQuery($sql,':domain_id', $domain_id);
	return $sth->fetch();
}

function getDefaultBiller($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT b.name AS name FROM ".TB_PREFIX."biller b, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'biller' AND b.id = s.value AND b.domain_id = s.domain_id AND s.domain_id = :domain_id)";
	$sth = dbQuery($sql,':domain_id', $domain_id);
	return $sth->fetch();
}

function getDefaultTax($domain_id='') {

	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."tax t, ".TB_PREFIX."system_defaults s WHERE s.name = 'tax' AND t.tax_id = s.value AND t.domain_id = s.domain_id AND s.domain_id = :domain_id";
	$sth = dbQuery($sql,':domain_id',$domain_id);
	return $sth->fetch();
}

function getDefaultDelete() {
	return getDefaultGeneric('delete');
}

function getDefaultLogging() {
	return getDefaultGeneric('logging');
}

function getDefaultLoggingStatus() {
	return (getDefaultGeneric('logging', false) == 1);
}

function getDefaultInventory() {
	return getDefaultGeneric('inventory');
}

function getDefaultProductAttributes() {
	return getDefaultGeneric('product_attributes');
}

function getDefaultLargeDataset() {
	return getDefaultGeneric('large_dataset');
}

function getDefaultLanguage() {
	return getDefaultGeneric('language', false);
}

function getInvoiceTotal($invoice_id, $domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql ="SELECT SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :invoice_id AND domain_id = :domain_id";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id,':domain_id', $domain_id);
	$res = $sth->fetch();
	return $res['total'];
}

function setInvoiceStatus($invoice, $status, $domain_id=''){

	$domain_id = domain_id::get($domain_id);

	$sql = "UPDATE " . TB_PREFIX . "invoices SET status_id =  :status WHERE id =  :id AND domain_id = :domain_id";
	$sth  = dbQuery($sql, ':status', $status, ':id', $invoice,':domain_id', $domain_id);
}

function getInvoice($id, $domain_id='') {
	global $config;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE id =  :id AND domain_id =  :domain_id";

	$sth  = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);

	$invoice = $sth->fetch();

	if (!$invoice) return false;

	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['date'] = siLocal::date( $invoice['date'] );

	$rawDue = $invoice['due_date'] ?? null;
	$invoice['calc_due_date'] = (!empty($rawDue) && $rawDue !== '0000-00-00') ? date('Y-m-d', strtotime($rawDue)) : '';
	if ($invoice['calc_due_date'] !== '') {
		$invoice['due_date'] = siLocal::date($rawDue);
	} else {
		$invoice['due_date'] = '';
	}
	$invoice['payment_term_label'] = '';
	$invoice['payment_term_code'] = '';
	if (!empty($invoice['payment_term_id'])) {
		$pt = getPaymentTerm($invoice['payment_term_id'], $domain_id);
		if ($pt) {
			$invoice['payment_term_label'] = $pt['term_label'];
			$invoice['payment_term_code'] = $pt['term_code'] ?? '';
		}
	}

	$invoice['total'] = getInvoiceTotal($invoice['id']);

	$invoiceobj = new invoice();
	$invoiceobj->domain_id = $domain_id;
	$invoice['gross'] = $invoiceobj->getInvoiceGross($invoice['id']);

	$invoice['paid'] = calc_invoice_paid($invoice['id']);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];

	#invoice total tax
	$sql ="SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :id AND domain_id =  :domain_id";
	$sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
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
function numberOfTaxesForInvoice($invoice_id, $domain_id='')
{
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT
				DISTINCT tax.tax_id
			FROM
				".TB_PREFIX."invoice_item_tax item_tax,
				".TB_PREFIX."invoice_items item,
				".TB_PREFIX."tax tax
			WHERE
				item.id = item_tax.invoice_item_id
			AND tax.tax_id = item_tax.tax_id
			AND tax.domain_id = item.domain_id
			AND item.invoice_id = :invoice_id
			AND tax.domain_id = :domain_id
			GROUP BY
				tax.tax_id;";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $domain_id);
	$result = count($sth->fetchAll());

	return $result;

}

/*
Function: taxesGroupedForInvoice
Purpose: to show a nice summary of total $ for tax for an invoice
*/
function taxesGroupedForInvoice($invoice_id, $domain_id='')
{
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT
				tax.tax_description as tax_name,
				SUM(item_tax.tax_amount) as tax_amount,
				MAX(item_tax.tax_rate) as tax_rate,
				count(*) as count
			FROM
				".TB_PREFIX."invoice_item_tax item_tax,
				".TB_PREFIX."invoice_items item,
				".TB_PREFIX."tax tax
			WHERE
				item.id = item_tax.invoice_item_id
			AND tax.tax_id = item_tax.tax_id
			AND tax.domain_id = item.domain_id
			AND item.invoice_id = :invoice_id
			AND tax.domain_id = :domain_id
			GROUP BY
				tax.tax_id;";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $domain_id);
	$result = $sth->fetchAll();

	return $result;

}

/*
Function: taxesGroupedForInvoiceItem
Purpose: to show a nice summary of total $ for tax for an invoice item - used for invoice editing
*/
function taxesGroupedForInvoiceItem($invoice_item_id, $domain_id='')
{
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT
				item_tax.id as row_id,
				tax.tax_description as tax_name,
				tax.tax_id as tax_id
			FROM
				".TB_PREFIX."invoice_item_tax item_tax,
				".TB_PREFIX."tax tax
			WHERE
				item_tax.invoice_item_id = :invoice_item_id
			AND tax.tax_id = item_tax.tax_id
			AND tax.domain_id = :domain_id
			ORDER BY
				row_id ASC;";
	$sth = dbQuery($sql, ':invoice_item_id', $invoice_item_id, ':domain_id', $domain_id);
	$result = $sth->fetchAll();

	return $result;

}

function setStatusExtension($extension_id, $status=2, $domain_id='') {

	$domain_id = domain_id::get($domain_id);

	//status=2 = toggle status
	if ($status == 2) {
		$sql = "SELECT enabled FROM ".TB_PREFIX."extensions WHERE id = :id AND domain_id = :domain_id LIMIT 1";
		$sth = dbQuery($sql,':id', $extension_id, ':domain_id', $domain_id);
		$extension_info = $sth->fetch();
		$status = 1 - $extension_info['enabled'];
	}

	$sql = "UPDATE ".TB_PREFIX."extensions SET enabled =  :status WHERE id =  :id AND domain_id =  :domain_id"; 
	if (dbQuery($sql, ':status', $status,':id', $extension_id, ':domain_id', $domain_id)) {
		return true;
	}
	return false;
}

function getExtensionID($extension_name = "none", $domain_id='') {
	// Core is always id 0 when used for system_defaults (extensions table may only have core row)
	if ($extension_name === 'core') {
		$domain_id = domain_id::get($domain_id);
		$sql = "SELECT id FROM ".TB_PREFIX."extensions WHERE name = 'core' AND (domain_id = 0 OR domain_id = :domain_id) ORDER BY domain_id DESC LIMIT 1";
		$sth = dbQuery($sql, ':domain_id', $domain_id);
		$row = $sth ? $sth->fetch() : null;
		return $row ? (int)$row['id'] : 0;
	}
	$domain_id = domain_id::get($domain_id);
	$sql = "SELECT * FROM ".TB_PREFIX."extensions WHERE name = :extension_name AND (domain_id = 0 OR domain_id = :domain_id) ORDER BY domain_id DESC LIMIT 1";
	$sth = dbQuery($sql, ':extension_name', $extension_name, ':domain_id', $domain_id);
	$extension_info = $sth ? $sth->fetch() : null;
	if (!$extension_info) { return -2; }
	if ($extension_info['enabled'] == 0) { return -1; }
	return (int)$extension_info['id'];
}

function getSystemDefaults($domain_id='') {

	$domain_id = domain_id::get($domain_id);

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
        
        $sth = $db->query($sql_default);	

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

        $sth = $db->query($sql_default, ':domain_id', 0);	
	}

	$defaults = null;
	$default = null;

	while($default = $sth->fetch()) {
		$defaults[$default['name']] = $default['value'];
	}

    if (getNumberOfDoneSQLPatches() > "198")
    {
        $sql  = "SELECT def.name,def.value FROM ".TB_PREFIX."system_defaults def ";
		$sql .= " INNER JOIN ".TB_PREFIX."extensions ext ON (def.extension_id = ext.id)";
        $sql .= " WHERE enabled=1";
        $sql .= " AND def.domain_id = :domain_id";
        $sql .= " ORDER BY extension_id ASC";		// order is important for overriding settings

        // add all settings from current domain
        //$sth = dbQuery($sql.$current_settings.$order, 'domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
        $sth = $db->query($sql, 'domain_id', $domain_id);
        $default = null;

        while($default = $sth->fetch()) {
            $defaults[$default['name']] = $default['value'];	// if setting is redefined, overwrite the previous value
        }
    }

	return $defaults;

}

function updateDefault($name,$value,$extension_name="core") {

	global $db_server;
	$domain_id = domain_id::get();

	$extension_id = getExtensionID($extension_name);
	if (!($extension_id >= 0))
	{
		die(htmlsafe("Invalid extension name: ".$extension_name));
	}

	if ($db_server == 'mysql') {
		$sql = "INSERT INTO `".TB_PREFIX."system_defaults`
			(`name`, `value`, domain_id, extension_id)
			VALUES (:name, :value, :domain_id, :extension_id)
			ON DUPLICATE KEY UPDATE `value` = :value";
	} else {
		// PostgreSQL 9.5+ / SQLite 3.24+ upsert syntax
		$sql = "INSERT INTO ".TB_PREFIX."system_defaults
			(name, value, domain_id, extension_id)
			VALUES (:name, :value, :domain_id, :extension_id)
			ON CONFLICT (domain_id, name) DO UPDATE SET value = EXCLUDED.value";
	}

	if (dbQuery($sql,
		':value', $value,
		':domain_id', $domain_id,
		':name', $name,
		':extension_id', $extension_id
		)
	) return true;
	return false;
}

function getInvoiceType($id) {

	$sql = "SELECT * FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :id";
	$sth = dbQuery($sql, ':id', $id);
	return $sth->fetch();
}

function insertBiller() {
	global $db_server;
	$domain_id = domain_id::get();
	$gkey = si_gateway_secrets_get_raw_key();

	// pgsql/sqlite: omit id column (auto-generated); mysql: explicit NULL triggers AUTO_INCREMENT
	if ($db_server == 'pgsql' || $db_server == 'sqlite') {
		$sql = "INSERT INTO ".TB_PREFIX."biller (
				domain_id, name, street_address, street_address2, city,
				state, zip_code, country, phone, mobile_phone,
				fax, email, logo, footer,
				paymentsgateway_api_id, notes, custom_field1,
				custom_field2, custom_field3, custom_field4, enabled,
				stripe_secret_key, stripe_webhook_secret, stripe_test_mode,
				paypal_client_id, paypal_client_secret, paypal_test_mode,
				mollie_api_key,
				authorizenet_login_id, authorizenet_transaction_key, authorizenet_signature_key, authorizenet_test_mode,
				eway_api_key, eway_api_password, eway_test_mode,
				kofi_username,
				coinbase_api_key, coinbase_webhook_secret,
				adyen_api_key, adyen_merchant_account, adyen_hmac_key, adyen_live_prefix, adyen_test_mode,
				bank_account_name, bank_name, bank_swift_bic, bank_account_number, bank_routing_sort_code
			) VALUES (
				:domain_id, :name, :street_address, :street_address2, :city,
				:state, :zip_code, :country, :phone, :mobile_phone,
				:fax, :email, :logo, :footer,
				:paymentsgateway_api_id, :notes, :custom_field1,
				:custom_field2, :custom_field3, :custom_field4, :enabled,
				:stripe_secret_key, :stripe_webhook_secret, :stripe_test_mode,
				:paypal_client_id, :paypal_client_secret, :paypal_test_mode,
				:mollie_api_key,
				:authorizenet_login_id, :authorizenet_transaction_key, :authorizenet_signature_key, :authorizenet_test_mode,
				:eway_api_key, :eway_api_password, :eway_test_mode,
				:kofi_username,
				:coinbase_api_key, :coinbase_webhook_secret,
				:adyen_api_key, :adyen_merchant_account, :adyen_hmac_key, :adyen_live_prefix, :adyen_test_mode,
				:bank_account_name, :bank_name, :bank_swift_bic, :bank_account_number, :bank_routing_sort_code
			)";
	} else {
		$sql = "INSERT INTO ".TB_PREFIX."biller (
				id, domain_id, name, street_address, street_address2, city,
				state, zip_code, country, phone, mobile_phone,
				fax, email, logo, footer,
				paymentsgateway_api_id, notes, custom_field1,
				custom_field2, custom_field3, custom_field4, enabled,
				stripe_secret_key, stripe_webhook_secret, stripe_test_mode,
				paypal_client_id, paypal_client_secret, paypal_test_mode,
				mollie_api_key,
				authorizenet_login_id, authorizenet_transaction_key, authorizenet_signature_key, authorizenet_test_mode,
				eway_api_key, eway_api_password, eway_test_mode,
				kofi_username,
				coinbase_api_key, coinbase_webhook_secret,
				adyen_api_key, adyen_merchant_account, adyen_hmac_key, adyen_live_prefix, adyen_test_mode,
				bank_account_name, bank_name, bank_swift_bic, bank_account_number, bank_routing_sort_code
			) VALUES (
				NULL, :domain_id, :name, :street_address, :street_address2, :city,
				:state, :zip_code, :country, :phone, :mobile_phone,
				:fax, :email, :logo, :footer,
				:paymentsgateway_api_id, :notes, :custom_field1,
				:custom_field2, :custom_field3, :custom_field4, :enabled,
				:stripe_secret_key, :stripe_webhook_secret, :stripe_test_mode,
				:paypal_client_id, :paypal_client_secret, :paypal_test_mode,
				:mollie_api_key,
				:authorizenet_login_id, :authorizenet_transaction_key, :authorizenet_signature_key, :authorizenet_test_mode,
				:eway_api_key, :eway_api_password, :eway_test_mode,
				:kofi_username,
				:coinbase_api_key, :coinbase_webhook_secret,
				:adyen_api_key, :adyen_merchant_account, :adyen_hmac_key, :adyen_live_prefix, :adyen_test_mode,
				:bank_account_name, :bank_name, :bank_swift_bic, :bank_account_number, :bank_routing_sort_code
			)";
	}

	return dbQuery($sql,
		':name', $_POST['name'],
		':street_address', $_POST['street_address'],
		':street_address2', $_POST['street_address2'],
		':city', $_POST['city'],
		':state', $_POST['state'],
		':zip_code', $_POST['zip_code'],
		':country', $_POST['country'],
		':phone', $_POST['phone'],
		':mobile_phone', $_POST['mobile_phone'],
		':fax', $_POST['fax'],
		':email', $_POST['email'],
		':logo', $_POST['logo'],
		':footer', $_POST['footer'],
		':paymentsgateway_api_id', $_POST['paymentsgateway_api_id'],
		':notes', $_POST['notes'],
		':custom_field1', $_POST['custom_field1'],
		':custom_field2', $_POST['custom_field2'],
		':custom_field3', $_POST['custom_field3'],
		':custom_field4', $_POST['custom_field4'],
		':enabled', $_POST['enabled'],
		':stripe_secret_key', si_gateway_secret_encrypt(trim((string) ($_POST['stripe_secret_key'] ?? '')), $gkey),
		':stripe_webhook_secret', si_gateway_secret_encrypt(trim((string) ($_POST['stripe_webhook_secret'] ?? '')), $gkey),
		':stripe_test_mode', (int) ($_POST['stripe_test_mode'] ?? 1),
		':paypal_client_id', $_POST['paypal_client_id'] ?? '',
		':paypal_client_secret', si_gateway_secret_encrypt(trim((string) ($_POST['paypal_client_secret'] ?? '')), $gkey),
		':paypal_test_mode', (int) ($_POST['paypal_test_mode'] ?? 1),
		':mollie_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['mollie_api_key'] ?? '')), $gkey),
		':authorizenet_login_id', si_gateway_secret_encrypt(trim((string) ($_POST['authorizenet_login_id'] ?? '')), $gkey),
		':authorizenet_transaction_key', si_gateway_secret_encrypt(trim((string) ($_POST['authorizenet_transaction_key'] ?? '')), $gkey),
		':authorizenet_signature_key', si_gateway_secret_encrypt(trim((string) ($_POST['authorizenet_signature_key'] ?? '')), $gkey),
		':authorizenet_test_mode', (int) ($_POST['authorizenet_test_mode'] ?? 1),
		':eway_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['eway_api_key'] ?? '')), $gkey),
		':eway_api_password', si_gateway_secret_encrypt(trim((string) ($_POST['eway_api_password'] ?? '')), $gkey),
		':eway_test_mode', (int) ($_POST['eway_test_mode'] ?? 1),
		':kofi_username', $_POST['kofi_username'] ?? '',
		':coinbase_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['coinbase_api_key'] ?? '')), $gkey),
		':coinbase_webhook_secret', si_gateway_secret_encrypt(trim((string) ($_POST['coinbase_webhook_secret'] ?? '')), $gkey),
		':adyen_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['adyen_api_key'] ?? '')), $gkey),
		':adyen_merchant_account', $_POST['adyen_merchant_account'] ?? '',
		':adyen_hmac_key', si_gateway_secret_encrypt(trim((string) ($_POST['adyen_hmac_key'] ?? '')), $gkey),
		':adyen_live_prefix', $_POST['adyen_live_prefix'] ?? '',
		':adyen_test_mode', (int) ($_POST['adyen_test_mode'] ?? 1),
		':bank_account_name', $_POST['bank_account_name'] ?? '',
		':bank_name', $_POST['bank_name'] ?? '',
		':bank_swift_bic', $_POST['bank_swift_bic'] ?? '',
		':bank_account_number', $_POST['bank_account_number'] ?? '',
		':bank_routing_sort_code', $_POST['bank_routing_sort_code'] ?? '',
		':domain_id', $domain_id
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

	$domain_id = domain_id::get();
	$gkey = si_gateway_secrets_get_raw_key();

	$sql = "UPDATE
				".TB_PREFIX."biller
			SET
				domain_id = :domain_id,
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
				paymentsgateway_api_id = :paymentsgateway_api_id,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				enabled = :enabled,
				stripe_secret_key = :stripe_secret_key,
				stripe_webhook_secret = :stripe_webhook_secret,
				stripe_test_mode = :stripe_test_mode,
				paypal_client_id = :paypal_client_id,
				paypal_client_secret = :paypal_client_secret,
				paypal_test_mode = :paypal_test_mode,
				mollie_api_key = :mollie_api_key,
				authorizenet_login_id = :authorizenet_login_id,
				authorizenet_transaction_key = :authorizenet_transaction_key,
				authorizenet_signature_key = :authorizenet_signature_key,
				authorizenet_test_mode = :authorizenet_test_mode,
				eway_api_key = :eway_api_key,
				eway_api_password = :eway_api_password,
				eway_test_mode = :eway_test_mode,
				kofi_username = :kofi_username,
				coinbase_api_key = :coinbase_api_key,
				coinbase_webhook_secret = :coinbase_webhook_secret,
				adyen_api_key = :adyen_api_key,
				adyen_merchant_account = :adyen_merchant_account,
				adyen_hmac_key = :adyen_hmac_key,
				adyen_live_prefix = :adyen_live_prefix,
				adyen_test_mode = :adyen_test_mode,
				bank_account_name = :bank_account_name,
				bank_name = :bank_name,
				bank_swift_bic = :bank_swift_bic,
				bank_account_number = :bank_account_number,
				bank_routing_sort_code = :bank_routing_sort_code
			WHERE
				id = :id
			AND domain_id = :domain_id";
	return dbQuery($sql,
		':domain_id', $domain_id,
		':name', $_POST['name'],
		':street_address', $_POST['street_address'],
		':street_address2', $_POST['street_address2'],
		':city', $_POST['city'],
		':state', $_POST['state'],
		':zip_code', $_POST['zip_code'],
		':country', $_POST['country'],
		':phone', $_POST['phone'],
		':mobile_phone', $_POST['mobile_phone'],
		':fax', $_POST['fax'],
		':email', $_POST['email'],
		':logo', $_POST['logo'],
		':footer', $_POST['footer'],
		':paymentsgateway_api_id', $_POST['paymentsgateway_api_id'],
		':notes', $_POST['notes'],
		':custom_field1', $_POST['custom_field1'],
		':custom_field2', $_POST['custom_field2'],
		':custom_field3', $_POST['custom_field3'],
		':custom_field4', $_POST['custom_field4'],
		':enabled', $_POST['enabled'],
		':stripe_secret_key', si_gateway_secret_encrypt(trim((string) ($_POST['stripe_secret_key'] ?? '')), $gkey),
		':stripe_webhook_secret', si_gateway_secret_encrypt(trim((string) ($_POST['stripe_webhook_secret'] ?? '')), $gkey),
		':stripe_test_mode', (int) ($_POST['stripe_test_mode'] ?? 1),
		':paypal_client_id', $_POST['paypal_client_id'] ?? '',
		':paypal_client_secret', si_gateway_secret_encrypt(trim((string) ($_POST['paypal_client_secret'] ?? '')), $gkey),
		':paypal_test_mode', (int) ($_POST['paypal_test_mode'] ?? 1),
		':mollie_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['mollie_api_key'] ?? '')), $gkey),
		':authorizenet_login_id', si_gateway_secret_encrypt(trim((string) ($_POST['authorizenet_login_id'] ?? '')), $gkey),
		':authorizenet_transaction_key', si_gateway_secret_encrypt(trim((string) ($_POST['authorizenet_transaction_key'] ?? '')), $gkey),
		':authorizenet_signature_key', si_gateway_secret_encrypt(trim((string) ($_POST['authorizenet_signature_key'] ?? '')), $gkey),
		':authorizenet_test_mode', (int) ($_POST['authorizenet_test_mode'] ?? 1),
		':eway_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['eway_api_key'] ?? '')), $gkey),
		':eway_api_password', si_gateway_secret_encrypt(trim((string) ($_POST['eway_api_password'] ?? '')), $gkey),
		':eway_test_mode', (int) ($_POST['eway_test_mode'] ?? 1),
		':kofi_username', $_POST['kofi_username'] ?? '',
		':coinbase_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['coinbase_api_key'] ?? '')), $gkey),
		':coinbase_webhook_secret', si_gateway_secret_encrypt(trim((string) ($_POST['coinbase_webhook_secret'] ?? '')), $gkey),
		':adyen_api_key', si_gateway_secret_encrypt(trim((string) ($_POST['adyen_api_key'] ?? '')), $gkey),
		':adyen_merchant_account', $_POST['adyen_merchant_account'] ?? '',
		':adyen_hmac_key', si_gateway_secret_encrypt(trim((string) ($_POST['adyen_hmac_key'] ?? '')), $gkey),
		':adyen_live_prefix', $_POST['adyen_live_prefix'] ?? '',
		':adyen_test_mode', (int) ($_POST['adyen_test_mode'] ?? 1),
		':bank_account_name', $_POST['bank_account_name'] ?? '',
		':bank_name', $_POST['bank_name'] ?? '',
		':bank_swift_bic', $_POST['bank_swift_bic'] ?? '',
		':bank_account_number', $_POST['bank_account_number'] ?? '',
		':bank_routing_sort_code', $_POST['bank_routing_sort_code'] ?? '',
		':id', $_GET['id']
		);
}

/**
 * Whether another customer in this domain already has the same name (trimmed, case-insensitive).
 *
 * @param string $name
 * @param int|null $excludeCustomerId When set, ignore this customer id (for edits).
 * @param string $domain_id
 */
function customerNameExists($name, $excludeCustomerId = null, $domain_id = '') {
	$domain_id = domain_id::get($domain_id);
	$norm = mb_strtolower(trim((string) $name), 'UTF-8');
	if ($norm === '') {
		return false;
	}
	$sql = "SELECT 1 FROM " . TB_PREFIX . "customers
		WHERE domain_id = :domain_id AND LOWER(TRIM(name)) = :norm";
	$args = [':domain_id', $domain_id, ':norm', $norm];
	if ($excludeCustomerId !== null && (int) $excludeCustomerId > 0) {
		$sql .= " AND id <> :exclude_id";
		$args[] = ':exclude_id';
		$args[] = (int) $excludeCustomerId;
	}
	$sql .= " LIMIT 1";
	$sth = dbQuery($sql, ...$args);
	return (bool) ($sth && $sth->fetch());
}

/**
 * Whether another product in this domain already has the same description (trimmed, case-insensitive).
 *
 * @param string $description
 * @param int|null $excludeProductId When set, ignore this product id (for edits).
 * @param string $domain_id
 */
function productDescriptionExists($description, $excludeProductId = null, $domain_id = '') {
	$domain_id = domain_id::get($domain_id);
	$norm = mb_strtolower(trim((string) $description), 'UTF-8');
	if ($norm === '') {
		return false;
	}
	$sql = "SELECT 1 FROM " . TB_PREFIX . "products
		WHERE domain_id = :domain_id AND LOWER(TRIM(description)) = :norm";
	$args = [':domain_id', $domain_id, ':norm', $norm];
	if ($excludeProductId !== null && (int) $excludeProductId > 0) {
		$sql .= " AND id <> :exclude_id";
		$args[] = ':exclude_id';
		$args[] = (int) $excludeProductId;
	}
	$sql .= " LIMIT 1";
	$sth = dbQuery($sql, ...$args);
	return (bool) ($sth && $sth->fetch());
}

/**
 * Whether another biller in this domain already has the same name (trimmed, case-insensitive).
 *
 * @param string $name
 * @param int|null $excludeBillerId When set, ignore this biller id (for edits).
 * @param string $domain_id
 */
function billerNameExists($name, $excludeBillerId = null, $domain_id = '') {
	$domain_id = domain_id::get($domain_id);
	$norm = mb_strtolower(trim((string) $name), 'UTF-8');
	if ($norm === '') {
		return false;
	}
	$sql = "SELECT 1 FROM " . TB_PREFIX . "biller
		WHERE domain_id = :domain_id AND LOWER(TRIM(name)) = :norm";
	$args = [':domain_id', $domain_id, ':norm', $norm];
	if ($excludeBillerId !== null && (int) $excludeBillerId > 0) {
		$sql .= " AND id <> :exclude_id";
		$args[] = ':exclude_id';
		$args[] = (int) $excludeBillerId;
	}
	$sql .= " LIMIT 1";
	$sth = dbQuery($sql, ...$args);
	return (bool) ($sth && $sth->fetch());
}

function updateCustomer() {
	global $config;
	$domain_id = domain_id::get();
	$nameTrim = trim((string) ($_POST['name'] ?? ''));
	if ($nameTrim !== '' && customerNameExists($nameTrim, (int) ($_GET['id'] ?? 0), $domain_id)) {
		return false;
	}
	$sql = "UPDATE
				".TB_PREFIX."customers
			SET
				domain_id = :domain_id,
				attention = :attention,
				name = :name,
				department = :department,
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
				id = :id
			AND domain_id = :domain_id";

		return dbQuery($sql,
			':domain_id', $domain_id,
			':attention', $_POST['attention'],
			':name', $_POST['name'],
			':department', $_POST['department'],
			':street_address', $_POST['street_address'],
			':street_address2', $_POST['street_address2'],
			':city', $_POST['city'],
			':state', $_POST['state'],
			':zip_code', $_POST['zip_code'],
			':country', $_POST['country'],
			':phone', $_POST['phone'],
			':mobile_phone', $_POST['mobile_phone'],
			':fax', $_POST['fax'],
			':email', $_POST['email'],
			':notes', $_POST['notes'],
			':custom_field1', $_POST['custom_field1'],
			':custom_field2', $_POST['custom_field2'],
			':custom_field3', $_POST['custom_field3'],
			':custom_field4', $_POST['custom_field4'],
			':enabled', $_POST['enabled'],
			':id', $_GET['id']
		);
}

function insertCustomer() {
    global $config;
	$domain_id = domain_id::get();
	$nameTrim = trim((string) ($_POST['name'] ?? ''));
	if ($nameTrim !== '' && customerNameExists($nameTrim, null, $domain_id)) {
		return false;
	}

	$sql = "INSERT INTO
			".TB_PREFIX."customers
			(
				domain_id, attention, name, department, street_address, street_address2,
				city, state, zip_code, country, phone, mobile_phone,
				fax, email, notes,
				custom_field1, custom_field2,
				custom_field3, custom_field4, enabled
			)
			VALUES
			(
				:domain_id ,:attention, :name, :department, :street_address, :street_address2,
				:city, :state, :zip_code, :country, :phone, :mobile_phone,
				:fax, :email, :notes,
				:custom_field1, :custom_field2,
				:custom_field3, :custom_field4, :enabled
			)";

	return dbQuery($sql,
		':attention',     $_POST['attention']      ?? '',
		':name',          $_POST['name']           ?? '',
		':department',    $_POST['department']     ?? '',
		':street_address',  $_POST['street_address']  ?? '',
		':street_address2', $_POST['street_address2'] ?? '',
		':city',          $_POST['city']           ?? '',
		':state',         $_POST['state']          ?? '',
		':zip_code',      $_POST['zip_code']       ?? '',
		':country',       $_POST['country']        ?? '',
		':phone',         $_POST['phone']          ?? '',
		':mobile_phone',  $_POST['mobile_phone']   ?? '',
		':fax',           $_POST['fax']            ?? '',
		':email',         $_POST['email']          ?? '',
		':notes',         $_POST['notes']          ?? '',
		':custom_field1', $_POST['custom_field1']  ?? '',
		':custom_field2', $_POST['custom_field2']  ?? '',
		':custom_field3', $_POST['custom_field3']  ?? '',
		':custom_field4', $_POST['custom_field4']  ?? '',
		':enabled',       $_POST['enabled']        ?? '',
		':domain_id',     $domain_id
		);

}

function searchCustomers($search) {
	global $db_server;
	$domain_id = domain_id::get();

	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id AND name LIKE :search";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id AND  name ILIKE :search";
	}
	$sth = dbQuery($sql, ':domain_id',$domain_id, ':search', "%$search%");

	$customers = array();

	for ($i = 0; $customer = $sth->fetch(); $i++) {
		$customers[$i] = $customer;
	}

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

function getCustomerInvoices($id, $domain_id='') {
	global $config;
	$domain_id = domain_id::get($domain_id);

// tested for MySQL	
	$sql = "SELECT	
		iv.id, 
		iv.index_id, 
		iv.date, 
		iv.type_id, 
		iv.denorm_invoice_total AS total,
		iv.denorm_amount_paid AS paid,
		iv.denorm_amount_owing AS owing,
		pr.status,
		pr.pref_inv_wording
	FROM 
		" . TB_PREFIX . "invoices iv
		LEFT JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
	WHERE 
		iv.customer_id = :id
	AND iv.domain_id = :domain_id
	ORDER BY 
		iv.id DESC;";	

	$sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);

	$invoices = null;
	while ($invoice = $sth->fetch()) {
		$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['date'] ) );
		$invoice['date'] = siLocal::date( $invoice['date'] );
		$invoices[] = $invoice;
	}
	return $invoices;

}

function getCustomers($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$customer = null;

	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
	$sth = dbQuery($sql,':domain_id', $domain_id);

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

function getActiveCustomers($domain_id='') {
	global $LANG;
	global $db_server;
	$domain_id = domain_id::get($domain_id);

	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled != 0 and domain_id = :domain_id ORDER BY name";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled and domain_id = :domain_id ORDER BY name";
	}
	$sth = dbQuery($sql,':domain_id', $domain_id);

	return $sth->fetchAll();
}

/* DELETE this function */
function getTopDebtor($domain_id='') {

  $domain_id = domain_id::get($domain_id);

  $debtor = null;

  #Largest debtor query - start

	$sql = "SELECT	
			c.id AS \"CID\",
	        c.name AS \"Customer\",
       		SUM(ii.total) AS \"Total\",
	        (SELECT COALESCE(SUM(ap.ac_amount), 0) FROM ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id AND ap.domain_id = iv2.domain_id) WHERE iv2.customer_id = c.id AND iv2.domain_id = c.domain_id) AS \"Paid\",
	        SUM(ii.total) - (SELECT COALESCE(SUM(ap.ac_amount), 0) FROM ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id AND ap.domain_id = iv2.domain_id) WHERE iv2.customer_id = c.id AND iv2.domain_id = c.domain_id) AS \"Owing\"
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

	$sth = dbQuery($sql, ':domain_id', $domain_id);

	$debtor = $sth->fetch();

  #Largest debtor query - end
  return $debtor;
}

/* DELETE this function */
function getTopCustomer($domain_id='') {

  $domain_id = domain_id::get($domain_id);

  $customer = null;

  #Top customer query - start

	$sql2 = "SELECT
			c.id AS \"CID\",
	        c.name AS \"Customer\",
       		SUM(ii.total) AS \"Total\",
	        (SELECT COALESCE(SUM(ap.ac_amount), 0) FROM ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id AND ap.domain_id = iv2.domain_id) WHERE iv2.customer_id = c.id AND iv2.domain_id = c.domain_id) AS \"Paid\",
	        SUM(ii.total) - (SELECT COALESCE(SUM(ap.ac_amount), 0) FROM ".TB_PREFIX."payment ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id AND ap.domain_id = iv2.domain_id) WHERE iv2.customer_id = c.id AND iv2.domain_id = c.domain_id) AS \"Owing\"
	FROM
       	".TB_PREFIX."customers c INNER JOIN
		".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND iv.domain_id = c.domain_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
	WHERE
		c.domain_id = :domain_id
	GROUP BY
	        \"CID\", iv.customer_id, \"Customer\"
	ORDER BY 
		\"Total\" DESC
	LIMIT 1;
";

	$tth = dbQuery($sql2,':domain_id',$domain_id);

	$customer = $tth->fetch();

  #Top customer query - end
  return $customer;
}

/* DELETE this function */
function getTopBiller($domain_id='') {

  $domain_id = domain_id::get($domain_id);

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

	$uth = dbQuery($sql3, ':domain_id', $domain_id);

	$biller = $uth->fetch();

  #Top biller query - start
  return $biller;
}

function insertTaxRate($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "INSERT into ".TB_PREFIX."tax
				(domain_id, tax_description, tax_percentage, type,  tax_enabled)
			VALUES
				(:domain_id, :description, :percent, :type, :enabled)";

	$display_block = $LANG['save_tax_rate_success'];
	if (!(dbQuery($sql,
		':domain_id', $domain_id,
		':description', $_POST['tax_description'],
		':percent', $_POST['tax_percentage'],
		':type', $_POST['type'],
		':enabled', $_POST['tax_enabled']))) {
		$display_block = $LANG['save_tax_rate_failure'];
	}
	return $display_block;
}

function updateTaxRate($domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$sql = "UPDATE
				".TB_PREFIX."tax
			SET
				tax_description = :description,
				tax_percentage = :percentage,
				type = :type,
				tax_enabled = :enabled
			WHERE
				tax_id = :id
			AND domain_id = :domain_id
			";

	$display_block = $LANG['save_tax_rate_success'];
	if (!(dbQuery($sql,
		':description', $_POST['tax_description'],
	  	':percentage', $_POST['tax_percentage'],
	  	':enabled', $_POST['tax_enabled'],
	  	':id', $_GET['id'],
	  	':domain_id', $domain_id,
	  	':type', $_POST['type']

		))) {
		$display_block = $LANG['save_tax_rate_failure'];
	}
	return $display_block;
}

//ensure we have a time, else add the current time to the date (to be sure invoices can be sorted by 'date desc')	
function SqlDateWithTime($in_date){
	list($date,$time)=explode(' ', $in_date);
	if(!$time or $time == '00:00:00'){
		$time=date('H:i:s');
	}
	$out_date="$date $time";
	return $out_date;
}

function insertInvoice($type, $domain_id='') {
	if (!class_exists('CurrencySignHelper')) {
		require_once __DIR__ . '/class/CurrencySignHelper.php';
	}
	require_once __DIR__ . '/class/PaymentTermCalculator.php';
	require_once __DIR__ . '/class/siCurrencies.php';
	global $db_server;
	$domain_id = domain_id::get($domain_id);

	if ($db_server == 'mysql' && !_invoice_check_fk(
		$_POST['biller_id'], $_POST['customer_id'],
		$type, $_POST['preference_id'])) {
		return null;
	}

	$pref_group=getPreference($_POST['preference_id'], $domain_id);

	// Snapshot currency from preference; allow override from POST (JS pre-populated fields).
	// Always store the decoded display form (€ not &#8364;) so JS comparisons work consistently.
	$currency_sign = CurrencySignHelper::forDisplay($_POST['currency_sign'] ?? $pref_group['pref_currency_sign'] ?? '');
	$currency_code = trim($_POST['currency_code'] ?? $pref_group['currency_code'] ?? '');

	// Resolve currency_id: user selection (POST) > lookup by sign+code > preference fallback
	$currency_id = !empty($_POST['currency_id']) ? (int) $_POST['currency_id'] : null;
	if (!$currency_id && ($currency_code !== '' || $currency_sign !== '')) {
		$currRow = siCurrencies::findByCode($domain_id, $currency_code ?: '')
			?: siCurrencies::findBySign($domain_id, $currency_sign);
		if ($currRow) {
			$currency_id = (int) $currRow['id'];
		}
	}
	if (!$currency_id && !empty($pref_group['currency_id'])) {
		$currRow = siCurrencies::getById((int) $pref_group['currency_id'], $domain_id);
		if ($currRow) {
			$currency_id = (int) $currRow['id'];
		}
	}

	// Position is managed by si_currency (via currency_id) first, then preference fallback
	$currency_position = '';
	if ($currency_id > 0) {
		$currRow = siCurrencies::getById($currency_id, $domain_id);
		if ($currRow) {
			$currency_position = $currRow['currency_position'] ?? '';
		}
	}
	if ($currency_position !== 'left' && $currency_position !== 'right') {
		$currency_position = trim($pref_group['currency_position'] ?? '');
	}
	if ($currency_position !== 'left' && $currency_position !== 'right') {
		$currency_position = CurrencySignHelper::defaultPositionForSign($currency_sign, $currency_code);
	}

	if (!$currency_id && ($currency_sign !== '' || $currency_code !== '')) {
		$currRow = siCurrencies::findOrCreate($domain_id, $currency_sign, $currency_code, $currency_position);
		if ($currRow) {
			$currency_id = (int) $currRow['id'];
		}
	}

	// Denormalise currency_code and currency_locale from preference
	if ($currency_id > 0 && ($currency_code === '')) {
		$tmpCur = siCurrencies::getById($currency_id, $domain_id);
		if ($tmpCur) {
			$currency_code = $tmpCur['currency_code'] ?? '';
		}
	}
	$currency_locale = trim($pref_group['locale'] ?? '');

	$ptId = isset($_POST['payment_term_id']) ? (int)$_POST['payment_term_id'] : 0;
	$termRow = ($ptId > 0) ? getPaymentTerm($ptId, $domain_id) : false;
	$paymentTermId = null;
	$dueDateSql = null;
	if ($termRow) {
		$paymentTermId = (int)$termRow['term_id'];
		$invYmd = substr($clean_date, 0, 10);
		$dueDateSql = PaymentTermCalculator::dueDateFromTerm($invYmd, $termRow);
	}

	if ($db_server == 'pgsql' || $db_server == 'sqlite') {
		// pgsql/sqlite: omit id column so the sequence/AUTOINCREMENT generates it
		$sql = "INSERT INTO ".TB_PREFIX."invoices (
				index_id, domain_id, biller_id, customer_id,
				type_id, preference_id, date, note,
				custom_field1, custom_field2, custom_field3, custom_field4,
				currency_sign, denorm_currency_code, denorm_currency_locale,
				currency_id,
				payment_term_id, due_date
			) VALUES (
				:index_id, :domain_id, :biller_id, :customer_id,
				:type, :preference_id, :date, :note,
				:customField1, :customField2, :customField3, :customField4,
				:currency_sign, :currency_code, :currency_locale,
				:currency_id,
				:payment_term_id, :due_date
			)";
	} else {
		// MySQL: explicit NULL triggers AUTO_INCREMENT
		$sql = "INSERT INTO ".TB_PREFIX."invoices (
				id, index_id, domain_id, biller_id, customer_id,
				type_id, preference_id, date, note,
				custom_field1, custom_field2, custom_field3, custom_field4,
				currency_sign, denorm_currency_code, denorm_currency_locale,
				currency_id,
				payment_term_id, due_date
			) VALUES (
				NULL, :index_id, :domain_id, :biller_id, :customer_id,
				:type, :preference_id, :date, :note,
				:customField1, :customField2, :customField3, :customField4,
				:currency_sign, :currency_code, :currency_locale,
				:currency_id,
				:payment_term_id, :due_date
			)";
	}

	$sth= dbQuery($sql,
		#':index_id', index::next('invoice',$pref_group['index_group'], $domain_id,$_POST['biller_id']),
		':index_id',		index::next('invoice',$pref_group['index_group'], $domain_id),
		':domain_id',		$domain_id,
		':biller_id',		$_POST['biller_id'],
		':customer_id', 	$_POST['customer_id'],
		':type', 			$type,
		':preference_id',	$_POST['preference_id'],
		':date', 			$clean_date,
		':note', 			trim($_POST['note']),
		':customField1',	$_POST['customField1'],
		':customField2',	$_POST['customField2'],
		':customField3',	$_POST['customField3'],
		':customField4',	$_POST['customField4'],
		':currency_sign',	$currency_sign,
		':currency_code',	$currency_code,
		':currency_locale',	$currency_locale,
		':currency_id',		$currency_id,
		':payment_term_id',	$paymentTermId,
		':due_date',		$dueDateSql
		);

    #index::increment('invoice',$pref_group['index_group'], $domain_id,$_POST['biller_id']);
	// Needed only if si_index table exists
    index::increment('invoice',$pref_group['index_group'], $domain_id);

    $insert_id = lastInsertId();
    if ($insert_id > 0) {
        invoice_denorm::refreshForInvoice((int) $insert_id, $domain_id);
    }

    return $sth;
}

function updateInvoice($invoice_id, $domain_id='') {
	if (!class_exists('CurrencySignHelper')) {
		require_once __DIR__ . '/class/CurrencySignHelper.php';
	}
	require_once __DIR__ . '/class/PaymentTermCalculator.php';
//  global $logger;
    global $db_server;
    $domain_id = domain_id::get($domain_id);

	$invoiceobj = new invoice();
    $current_invoice = $invoiceobj->select($invoice_id, $domain_id);
    $current_pref_group = getPreference($current_invoice['preference_id'], $domain_id);

    $new_pref_group=getPreference($_POST['preference_id'], $domain_id);

    $index_id = $current_invoice['index_id'];

//	$logger->log('Curent Index Group: '.$description, LegacyLogger::INFO);
//	$logger->log('Description: '.$description, LegacyLogger::INFO);

    if ($current_pref_group['index_group'] != $new_pref_group['index_group'])
    {
        $index_id = index::increment('invoice',$new_pref_group['index_group']);
    }

	// Invoice type for FK check (insertInvoice receives $type as a parameter; here use POST or existing row)
	$invoice_type = $_POST['type'] ?? $current_invoice['type_id'];
	if ($db_server == 'mysql' && !_invoice_check_fk(
		$_POST['biller_id'], $_POST['customer_id'],
		$invoice_type, $_POST['preference_id'])) {
		return null;
	}
	// Snapshot currency from preference; allow override from POST (JS pre-populated fields).
	// Always store decoded display form (€ not &#8364;) for consistent JS comparisons.
	$currency_sign = CurrencySignHelper::forDisplay($_POST['currency_sign'] ?? $new_pref_group['pref_currency_sign'] ?? '');
	$currency_code = trim($_POST['currency_code'] ?? $new_pref_group['currency_code'] ?? '');

	// Resolve currency_id: user selection (POST) > lookup by sign+code > preference fallback
	require_once __DIR__ . '/class/siCurrencies.php';
	$currency_id = !empty($_POST['currency_id']) ? (int) $_POST['currency_id'] : null;
	if (!$currency_id && ($currency_code !== '' || $currency_sign !== '')) {
		$currRow = siCurrencies::findByCode($domain_id, $currency_code ?: '')
			?: siCurrencies::findBySign($domain_id, $currency_sign);
		if ($currRow) {
			$currency_id = (int) $currRow['id'];
		}
	}
	if (!$currency_id && !empty($new_pref_group['currency_id'])) {
		$currRow = siCurrencies::getById((int) $new_pref_group['currency_id'], $domain_id);
		if ($currRow) {
			$currency_id = (int) $currRow['id'];
		}
	}

	// Position is managed by si_currency (via currency_id) first, then preference fallback
	$currency_position = '';
	if ($currency_id > 0) {
		$currRow = siCurrencies::getById($currency_id, $domain_id);
		if ($currRow) {
			$currency_position = $currRow['currency_position'] ?? '';
		}
	}
	if ($currency_position !== 'left' && $currency_position !== 'right') {
		$currency_position = trim($new_pref_group['currency_position'] ?? '');
	}
	if ($currency_position !== 'left' && $currency_position !== 'right') {
		$currency_position = CurrencySignHelper::defaultPositionForSign($currency_sign, $currency_code);
	}

	if (!$currency_id && ($currency_sign !== '' || $currency_code !== '')) {
		$currRow = siCurrencies::findOrCreate($domain_id, $currency_sign, $currency_code, $currency_position);
		if ($currRow) {
			$currency_id = (int) $currRow['id'];
		}
	}

	// Denormalise currency_code and currency_locale from preference
	if ($currency_id > 0 && ($currency_code === '')) {
		$tmpCur = siCurrencies::getById($currency_id, $domain_id);
		if ($tmpCur) {
			$currency_code = $tmpCur['currency_code'] ?? '';
		}
	}
	$currency_locale = trim($new_pref_group['locale'] ?? '');

	$clean_date = SqlDateWithTime($_POST['date']);
	$ptId = isset($_POST['payment_term_id']) ? (int)$_POST['payment_term_id'] : 0;
	$termRow = ($ptId > 0) ? getPaymentTerm($ptId, $domain_id) : false;
	$paymentTermId = null;
	$dueDateSql = null;
	if ($termRow) {
		$paymentTermId = (int)$termRow['term_id'];
		$invYmd = substr($clean_date, 0, 10);
		$dueDateSql = PaymentTermCalculator::dueDateFromTerm($invYmd, $termRow);
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
			custom_field4 = :customField4,
			currency_sign = :currency_sign,
			denorm_currency_code = :currency_code,
			denorm_currency_locale = :currency_locale,
			currency_id = :currency_id,
			payment_term_id = :payment_term_id,
			due_date = :due_date
		WHERE
			id = :invoice_id
		AND domain_id = :domain_id";

	$ok = dbQuery($sql,
        ':index_id', $index_id,
		':biller_id', $_POST['biller_id'],
		':customer_id', $_POST['customer_id'],
		':preference_id', $_POST['preference_id'],
		':date', $clean_date,
		':note', trim($_POST['note']),
		':customField1', $_POST['customField1'],
		':customField2', $_POST['customField2'],
		':customField3', $_POST['customField3'],
		':customField4', $_POST['customField4'],
		':currency_sign', $currency_sign,
		':currency_code', $currency_code,
		':currency_locale', $currency_locale,
		':currency_id', $currency_id,
		':payment_term_id', $paymentTermId,
		':due_date', $dueDateSql,
		':invoice_id', $invoice_id,
		':domain_id', $domain_id
		);
	if ($ok) {
		invoice_denorm::refreshForInvoice((int) $invoice_id, $domain_id);
	}
	return $ok;
}

function insertInvoiceItem($invoice_id,$quantity,$product_id,$line_number,$line_item_tax_id,$description="", $unit_price="", $attribute="", $domain_id='') {

	global $logger;
	global $db_server;
	global $LANG;
    $domain_id = domain_id::get($domain_id);

	if (!is_array($line_item_tax_id)) {
		$line_item_tax_id = ($line_item_tax_id === '' || $line_item_tax_id === null) ? array() : array($line_item_tax_id);
	}
	if (!is_array($attribute)) {
		$attribute = array();
	}

    //do taxes

    $attr = array();
	$logger->log('Line item attributes: '.var_export($attribute,true), LegacyLogger::INFO);
    foreach($attribute as $k=>$v)
    {
        if($attribute[$v] !== '')
        {
            $attr[$k] = $v;
        }
        
    }

	$tax_total = getTaxesPerLineItem($line_item_tax_id,$quantity, $unit_price, $domain_id);

	$logger->log(' ', LegacyLogger::INFO);
	$logger->log(' ', LegacyLogger::INFO);
	$logger->log('Invoice: '.$invoice_id.' Tax '.$line_item_tax_id.' for line item '.$line_number.': '.$tax_total, LegacyLogger::INFO);
	$logger->log('Description: '.$description, LegacyLogger::INFO);
	$logger->log(' ', LegacyLogger::INFO);

	//line item gross total
	$gross_total = $unit_price  * $quantity;

	//line item total
	$total = $gross_total + $tax_total;	

	//Remove auto-fill description - refer include/js/si-conf.blade.php autofill section
	if ($description == $LANG['description'])
	{	
		$description ="";
	}

	if ($db_server == 'mysql') {
		$tax_ids = is_array($line_item_tax_id) ? $line_item_tax_id : array($line_item_tax_id);
		$checked_tax = false;
		foreach ($tax_ids as $tid) {
			if ($tid === '' || $tid === null) {
				continue;
			}
			$checked_tax = true;
			if (!_invoice_items_check_fk($invoice_id, $product_id, $tid, null)) {
				return null;
			}
		}
		if (!$checked_tax && !_invoice_items_check_fk($invoice_id, $product_id, null, null)) {
			return null;
		}
	}
	$sql = "INSERT INTO ".TB_PREFIX."invoice_items 
			(
				invoice_id, 
				domain_id, 
				quantity, 
				product_id, 
				unit_price, 
				tax_amount, 
				gross_total, 
				description, 
                total,
                attribute
			) 
			VALUES 
			(
				:invoice_id, 
				:domain_id,
				:quantity, 
				:product_id, 
				:unit_price, 
				:tax_amount, 
				:gross_total, 
				:description, 
                :total,
                :attribute
			)";


	dbQuery($sql,
		':invoice_id', $invoice_id,
		':domain_id', $domain_id,
		':quantity', $quantity,
		':product_id', $product_id,
		':unit_price', $unit_price,
	//	':tax_id', $tax['tax_id'],
	//	':tax_percentage', $tax['tax_percentage'],
		':tax_amount', $tax_total,
		':gross_total', $gross_total,
		':description', trim($description),
        ':total', $total,
        ':attribute',json_encode($attr)
		);

	invoice_item_tax(lastInsertId(),$line_item_tax_id,$unit_price,$quantity,"insert");
	//TODO fix this
	return true;
}

/*
Function: getTaxesPerLineItem
Purpose: get the total tax for the line item
*/
function getTaxesPerLineItem($line_item_tax_id, $quantity, $unit_price, $domain_id='')
{
	global $logger;
    $domain_id = domain_id::get($domain_id);

	$tax_total = 0;

	foreach($line_item_tax_id as $key => $value) 
	{
		$logger->log("Key: ".$key." Value: ".$value, LegacyLogger::INFO);
		$tax = getTaxRate($value, $domain_id);
		$logger->log('tax rate: '.$tax['tax_percentage'], LegacyLogger::INFO);

		$tax_amount = lineItemTaxCalc($tax, $unit_price, $quantity);
		//get Total tax for line item
		$tax_total = $tax_total + $tax_amount;

		//$logger->log('Qty: '.$quantity.' Unit price: '.$unit_price, LegacyLogger::INFO);
		//$logger->log('Tax rate: '.$tax['tax_percentage'].' Tax type: '.$tax['tax_type'].' Tax $: '.$tax_amount, LegacyLogger::INFO);

	}
	return $tax_total;
}

/*
Function: lineItemTaxCalc
Purpose: do the calc for the tax for tax x on line item y
*/
function lineItemTaxCalc($tax, $unit_price, $quantity)
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
function invoice_item_tax($invoice_item_id, $line_item_tax_id, $unit_price, $quantity, $action='', $domain_id='') {
	
	global $logger;
    $domain_id = domain_id::get($domain_id);

	//if editing invoice delete all tax info then insert first then do insert again
	//probably can be done without delete - someone to look into this if required - TODO
	if ($action =="update")
	{

		$sql_delete = "DELETE from
							".TB_PREFIX."invoice_item_tax
					   WHERE
							invoice_item_id = :invoice_item_id";
		$logger->log("Invoice item: ".$invoice_item_id." tax lines deleted", LegacyLogger::INFO);

		dbQuery($sql_delete,':invoice_item_id',$invoice_item_id);

	}

	foreach($line_item_tax_id as $key => $value) 
	{
		if($value !== "")
		{
			$tax = getTaxRate($value, $domain_id);

			$logger->log("ITEM :: Key: ".$key." Value: ".$value, LegacyLogger::INFO);
			$logger->log('ITEM :: tax rate: '.$tax['tax_percentage'], LegacyLogger::INFO);
			$logger->log('ITEM :: domain_id: '.$domain_id, LegacyLogger::INFO);

			$tax_amount = lineItemTaxCalc($tax,$unit_price,$quantity);
			//get Total tax for line item (unused here)
			// $tax_total = $tax_total + $tax_amount;

			$logger->log('ITEM :: Qty: '.$quantity.' Unit price: '.$unit_price, LegacyLogger::INFO);
			$logger->log('ITEM :: Tax rate: '.$tax['tax_percentage'].' Tax type: '.$tax['type'].' Tax $: '.$tax_amount, LegacyLogger::INFO);

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
				':tax_id', $tax['tax_id'],
				':tax_type', $tax['type'],
				':tax_rate', $tax['tax_percentage'],
				':tax_amount', $tax_amount
				);
		}
	}
	//TODO fix this
	return true;
}

function updateInvoiceItem($id, $quantity, $product_id, $line_number, $line_item_tax_id, $description, $unit_price, $attribute="", $domain_id='') {

	global $logger;
	global $LANG;
	global $db_server;
    $domain_id = domain_id::get($domain_id);

	if (!is_array($line_item_tax_id)) {
		$line_item_tax_id = ($line_item_tax_id === '' || $line_item_tax_id === null) ? array() : array($line_item_tax_id);
	}
	if (!is_array($attribute)) {
		$attribute = array();
	}

	//$product = getProduct($product_id);
	//$tax = getTaxRate($tax_id);

    $attr = array();
	$logger->log('Line item attributes: '.var_export($attribute,true), LegacyLogger::INFO);
    foreach($attribute as $k=>$v)
    {
        if($attribute[$v] !== '')
        {
            $attr[$k] = $v;
        }

    }

	$tax_total = getTaxesPerLineItem($line_item_tax_id,$quantity, $unit_price);

	$logger->log('Line item id: '.$id.' Tax '.$line_item_tax_id.' for line item '.$line_number.': '.$tax_total, LegacyLogger::INFO);
	$logger->log('Description: '.$description, LegacyLogger::INFO);
	$logger->log(' ', LegacyLogger::INFO);

	//line item gross total
	$gross_total = $unit_price  * $quantity;

	//line item total
	$total = $gross_total + $tax_total;	

	//Remove auto-fill description - refer include/js/si-conf.blade.php autofill section
	if ($description == $LANG['description'])
	{	
		$description ="";
	}

	if ($db_server == 'mysql') {
		$tax_ids = is_array($line_item_tax_id) ? $line_item_tax_id : array($line_item_tax_id);
		$checked_tax = false;
		foreach ($tax_ids as $tid) {
			if ($tid === '' || $tid === null) {
				continue;
			}
			$checked_tax = true;
			if (!_invoice_items_check_fk(null, $product_id, $tid, 'update')) {
				return null;
			}
		}
		if (!$checked_tax && !_invoice_items_check_fk(null, $product_id, null, 'update')) {
			return null;
		}
	}

	$sql = "UPDATE ".TB_PREFIX."invoice_items 
	SET quantity =  :quantity,
	product_id = :product_id,
	unit_price = :unit_price,
	tax_amount = :tax_amount,
	gross_total = :gross_total,
	description = :description,
	total = :total,			
	attribute = :attribute			
	WHERE id = :id AND domain_id = :domain_id";

	dbQuery($sql,
		':quantity', $quantity,
		':product_id', $product_id,
		':unit_price', $unit_price,
		':tax_amount', $tax_total,
		':gross_total', $gross_total,
		':description', $description,
		':total', $total,
        ':attribute',json_encode($attr),
		':id', $id,
		':domain_id', $domain_id
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
		<li><a href='".$tempentry['link']."'>".htmlsafe($tempentry['name'])."</a>
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

function delete($module, $idField, $id, $domain_id='') {
	global $dbh;
	global $logger;
    $domain_id = domain_id::get($domain_id);

	$has_domain_id = false;

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
			$has_domain_id = true;
			// Check for use of product
			$sth = $dbh->prepare('SELECT count(*)
				FROM '.TB_PREFIX.'invoice_items
				WHERE product_id = :id AND domain_id = :domain_id');
			$sth->execute(array(':id' => $id, ':domain_id' => $domain_id));
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
			$has_domain_id = true;
			// Check for existant payments and line items
			$sth = $dbh->prepare('SELECT count(*) FROM (
				SELECT id FROM '.TB_PREFIX.'invoice_items
				WHERE invoice_id = :id AND domain_id = :domain_id
				UNION ALL
				SELECT id FROM '.TB_PREFIX.'payment
				WHERE ac_inv_id = :id AND domain_id = :domain_id) x');
			$sth->execute(array(':id' => $id, ':domain_id' => $domain_id));
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
	if ($has_domain_id) $sql .= " AND domain_id = :domain_id";
    $logger->log("Item deleted: ".$sql, LegacyLogger::INFO);
	if ($has_domain_id) 
		return dbQuery($sql, ':id', $id, ':domain_id',$domain_id);
	else
		return dbQuery($sql, ':id', $id);
}

function maxInvoice($domain_id='') {

	global $LANG;
    $domain_id = domain_id::get($domain_id);

	$sql = "SELECT max(id) as maxId FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";

	$sth = dbQuery($sql, ':domain_id', $domain_id);
	return $sth->fetch();

//while ($Array_max = mysql_fetch_array($result_max) ) {
//$max_invoice_id = $Array_max['max_inv_id'];
};

//in this file are functions for all sql queries
function checkTableExists($table = "" ) {

	if ($table == "") $table = TB_PREFIX."biller";

	global $LANG;
	global $dbh;
	global $config;
	switch ($config->database->adapter)
	{
		case "pdo_pgsql":
			$sth = $dbh->prepare('SELECT 1 FROM pg_tables WHERE schemaname = current_schema() AND tablename = :table LIMIT 1');
			$sth->execute(array(':table' => $table));
			break;

		case "pdo_sqlite":
			$sth = $dbh->prepare("SELECT 1 FROM sqlite_master WHERE type='table' AND name = :table LIMIT 1");
			$sth->execute(array(':table' => $table));
			break;

		case "pdo_mysql":
		default:
			$sth = $dbh->prepare("SHOW TABLES LIKE :table");
			$sth->execute(array(':table' => $table));
			break;
	}

	if ($sth && $sth->fetch())
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

	if ($db_server == 'pgsql') {
		$sql = "SELECT 1 FROM pg_attribute a INNER JOIN pg_class c ON (a.attrelid = c.oid) WHERE c.relkind = 'r' AND c.relname = :table AND a.attname = :field AND NOT a.attisdropped AND a.attnum > 0 LIMIT 1";
	} elseif ($db_server == 'sqlite') {
		// Use pragma_table_info() table-valued function (SQLite 3.16.0+)
		$sql = "SELECT 1 FROM pragma_table_info(:table) WHERE name = :field LIMIT 1";
	} else {
		$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE column_name = :field AND table_name = :table LIMIT 1";
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

/**
 * Whether a MySQL index with the given name exists on the table (current database).
 * Used to make SQL patches safe when older installs already match newer schema.
 */
function checkMysqlIndexExists($table, $indexName) {
	global $dbh;
	global $config;
	if ($config->database->adapter !== 'pdo_mysql') {
		return false;
	}
	$sql = "SELECT 1 FROM information_schema.statistics
		WHERE table_schema = DATABASE()
		AND table_name = :table
		AND index_name = :index_name
		LIMIT 1";
	$sth = $dbh->prepare($sql);
	if ($sth && $sth->execute(array(':table' => $table, ':index_name' => $indexName))) {
		return (bool) $sth->fetch();
	}
	return false;
}

/**
 * ALTER TABLE for InnoDB migration: add secondary KEY on column if missing, else engine-only.
 */
function mysqlPatchAlterInnoDbWithKeyIfMissing($tableSuffix, $indexName, $columnName) {
	$table = TB_PREFIX . $tableSuffix;
	if (checkMysqlIndexExists($table, $indexName)) {
		return "ALTER TABLE `{$table}` ENGINE=InnoDB";
	}
	return "ALTER TABLE `{$table}` ADD KEY `{$indexName}` (`{$columnName}`), ENGINE=InnoDB";
}

/**
 * Locale / language tokens substituted into essential_data.json (LOCALE, LANGUAGE).
 */
function install_essential_data_locale_tokens(?string $installLanguage = null): array {
	$loc = 'en_GB';
	if ($installLanguage !== null && trim($installLanguage) !== '') {
		$candidate = si_normalize_registration_language($installLanguage);
		if ($candidate !== '') {
			$loc = $candidate;
		}
	} elseif (function_exists('getSystemDefaults') && function_exists('checkTableExists')
		&& checkTableExists(TB_PREFIX . 'system_defaults')) {
		$defaults = getSystemDefaults();
		if (is_array($defaults) && !empty($defaults['language'])) {
			$loc = (string) $defaults['language'];
		}
	}
	return [$loc, $loc];
}

/**
 * Keys in essential_data.json that are global or first-install-only. They must not
 * be re-imported when provisioning a new domain on an existing database.
 */
function install_essential_data_global_table_keys(): array {
	return [
		'si_sql_patchmanager',
		'si_extensions',
		'si_invoice_type',
		'si_products_attribute_type',
		'si_user',
		'si_user_domain',
		'si_user_role',
	];
}

/**
 * Build concatenated INSERT SQL from essential_data.json for one domain.
 *
 * @param int         $targetDomainId      Substituted for DOMAIN-ID placeholders.
 * @param bool        $includeGlobalTables If false, omit patch manager, roles, demo user, etc.
 * @param string|null $domainUiLanguage    If set, LOCALE/LANGUAGE in essential_data (e.g. public registration).
 */
function install_build_essential_data_sql(int $targetDomainId, bool $includeGlobalTables, ?string $domainUiLanguage = null): string {
	$path = realpath(__DIR__ . '/../databases/json/essential_data.json');
	if ($path === false || !is_readable($path)) {
		throw new RuntimeException('essential_data.json not found or not readable');
	}
	$data = json_decode(file_get_contents($path), true);
	if (!is_array($data)) {
		throw new RuntimeException('essential_data.json is not valid JSON');
	}
	if (!$includeGlobalTables) {
		foreach (install_essential_data_global_table_keys() as $key) {
			unset($data[$key]);
		}
	}
	$json = json_encode($data);
	if ($json === false) {
		throw new RuntimeException('Failed to encode essential data');
	}
	if ($domainUiLanguage !== null && $domainUiLanguage !== '') {
		$locale          = si_normalize_registration_language($domainUiLanguage);
		$languageToken   = $locale;
	} else {
		[$locale, $languageToken] = install_essential_data_locale_tokens();
	}
	$pattern_find    = ['si_', 'DOMAIN-ID', 'LOCALE', 'LANGUAGE'];
	$pattern_replace = [TB_PREFIX, (string) $targetDomainId, $locale, $languageToken];
	$json = str_replace($pattern_find, $pattern_replace, $json);
	$decoded = json_decode($json, true);
	if (!is_array($decoded)) {
		throw new RuntimeException('essential data replace produced invalid JSON');
	}
	$ij = new importjson();
	return $ij->process($decoded);
}

/**
 * Whether essential bootstrap rows exist for a domain (custom fields + preferences).
 */
function domainHasEssentialBootstrapData(int $domainId): bool {
	if (!checkTableExists(TB_PREFIX . 'custom_fields') || !checkTableExists(TB_PREFIX . 'preferences')) {
		return false;
	}
	try {
		$sth = dbQuery(
			'SELECT 1 FROM ' . TB_PREFIX . 'custom_fields WHERE domain_id = :d LIMIT 1',
			':d',
			$domainId
		);
		if (!$sth || !$sth->fetch()) {
			return false;
		}
		$sth = dbQuery(
			'SELECT 1 FROM ' . TB_PREFIX . 'preferences WHERE domain_id = :d LIMIT 1',
			':d',
			$domainId
		);
		return (bool) ($sth && $sth->fetch());
	} catch (Exception $e) {
		return false;
	}
}

/**
 * Whether essential data exists for the current (or given) domain.
 *
 * When the database has no recorded patches yet, behaviour matches a fresh
 * database: we only look for bootstrap rows for the domain (not other domains).
 *
 * @param int|null $domainId Defaults to the authenticated session domain, or 1.
 */
function checkDataExists(?int $domainId = null): bool {
	global $auth_session;
	if ($domainId === null) {
		$domainId = isset($auth_session->domain_id) ? (int) $auth_session->domain_id : 1;
	}
	return domainHasEssentialBootstrapData($domainId);
}

/**
 * Whether the original client request used HTTPS (works behind reverse proxies
 * that terminate TLS and set X-Forwarded-Proto / Forwarded).
 *
 * Pangolin (Traefik-based) may send X-Forwarded-Proto: wss for WebSocket upgrades;
 * that is treated as HTTPS here so invoice/CSS absolute URLs use https://.
 *
 * Configure your proxy to set these from the edge connection only; do not forward
 * client-supplied X-Forwarded-* from untrusted peers.
 */
function si_request_is_https(): bool
{
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
		return true;
	}
	if (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') {
		return true;
	}
	$xfProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
	if (is_string($xfProto) && $xfProto !== '') {
		$first = strtolower(trim(explode(',', $xfProto)[0]));
		// https: normal reverse proxies. wss: some stacks (e.g. Traefik behind Pangolin)
		// send the WebSocket scheme; treat as TLS-terminated HTTPS for absolute URLs.
		if ($first === 'https' || $first === 'wss') {
			return true;
		}
	}
	$forwarded = $_SERVER['HTTP_FORWARDED'] ?? '';
	if (is_string($forwarded) && preg_match('/\bproto=(https|wss)\b/i', $forwarded)) {
		return true;
	}
	return false;
}

/**
 * Host for absolute URLs (public host when behind a reverse proxy).
 */
function si_request_public_http_host(): string
{
	$xfHost = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? '';
	if (is_string($xfHost) && $xfHost !== '') {
		$first = trim(explode(',', $xfHost)[0]);
		if ($first !== '') {
			return $first;
		}
	}
	return (string) ($_SERVER['HTTP_HOST'] ?? '');
}

function getURL()
{
	global $config;

	$port = "";
	$dir = dirname($_SERVER['PHP_SELF'] ?? '');
	//remove incorrect slashes for WinXP etc.
 $dir = str_replace('\\','',$dir);

	$_SERVER['FULL_URL'] = si_request_is_https() ? 'https://' : 'http://';

	$host = si_request_public_http_host();
	$_SERVER['FULL_URL'] .= $config->authentication->http . $host . $dir;

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
	$sth = dbQuery($check_patches_sql);

	$patches = $sth->fetch();

	//Returns number of patches applied
	return $patches['count'];
}

function pdfThis($html, $file_location = '', $pdfname = 'invoice')
{

	global $config;

	// Load Composer autoloader (mPDF)
	require_once('./vendor/autoload.php');

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
	if(!function_exists('convert_to_pdf'))
	{
		function convert_to_pdf($html_to_pdf, $pdfname, $file_location="") {

			global $config;
			$sysDefaults = getSystemDefaults();

			try {
				// Convert app URLs to local filesystem paths so mPDF can load CSS/images
				// without fetching from localhost (which often fails in CLI/Docker/local dev)
				$base_url = rtrim(getURL(), '/');
				$app_root = str_replace('\\', '/', realpath(dirname(__DIR__)));
				if ($base_url !== '' && $app_root !== false) {
					$html_to_pdf = str_replace($base_url . '/', $app_root . '/', $html_to_pdf);
					$html_to_pdf = str_replace($base_url, $app_root, $html_to_pdf);
					// Decode URL-encoded path segments (e.g. %20) in local paths so fopen() can open them
					$html_to_pdf = preg_replace_callback(
						'/\b(href|src)=(["\'])([^\2]*?)\2/',
						function ($m) use ($app_root) {
							$val = $m[3];
							if (strpos($val, $app_root) === 0) {
								$val = rawurldecode($val);
							}
							return $m[1] . '=' . $m[2] . $val . $m[2];
						},
						$html_to_pdf
					);
				}

				// Inline stylesheets so PDF is always styled (mPDF often doesn't load linked CSS
				// when base_url is wrong or in Docker/CLI). Only inline local paths under app root.
				if ($app_root !== false) {
					$html_to_pdf = preg_replace_callback(
						'/<link\s+[^>]*rel\s*=\s*["\']stylesheet["\'][^>]*href\s*=\s*["\']([^"\']+)["\'][^>]*\s*\/?>/i',
						function ($m) use ($app_root) {
							$path = rawurldecode($m[1]);
							$path = str_replace('\\', '/', $path);
							// Already a local absolute path under app root (after URL replacement above)
							if (strpos($path, $app_root) === 0) {
								// use as-is
							} else {
								// URL or relative path: take path segment (e.g. /templates/invoices/tabler/style.css)
								$pathSegment = $path;
								if (preg_match('#^https?://[^/]+(/.+)$#', $path, $urlParts)) {
									$pathSegment = $urlParts[1];
								} elseif ($path !== '' && $path[0] !== '/') {
									$pathSegment = '/' . $path;
								}
								$path = $app_root . $pathSegment;
							}
							if (!is_file($path) || !is_readable($path)) {
								return $m[0];
							}
							$real = str_replace('\\', '/', realpath($path));
							if ($real === false || strpos($real, $app_root) !== 0) {
								return $m[0];
							}
							$css = file_get_contents($path);
							if ($css === false) {
								return $m[0];
							}
							return '<style type="text/css">' . $css . '</style>';
						},
						$html_to_pdf
					);
				}

				// Resolve relative url() paths in background-image (and other CSS
				// properties) to absolute filesystem paths.  When stylesheets are
				// inlined into <style> tags the browser/mPDF can no longer resolve
				// relative paths against the CSS file's directory, so we rewrite
				// them here.  Where possible we embed as data-URIs (base64) for
				// maximum PDF compatibility.
				if ($app_root !== false) {
					$html_to_pdf = preg_replace_callback(
						'/url\(\s*["\']?\s*(\.\.\/[^)\s"\']+|\.\/[^)\s"\']+)\s*["\']?\s*\)/',
						function ($m) use ($app_root) {
							$rel = $m[1];
							$rel = str_replace('\\', '/', $rel);
							$resolved = null;
							// Try to resolve against known invoice template directories
							$dirs = glob($app_root . '/templates/invoices/*/style.css');
							foreach ($dirs as $cssFile) {
								$cssDir = str_replace('\\', '/', dirname($cssFile));
								$candidate = rtrim($cssDir, '/') . '/' . $rel;
								// Normalise ../ segments
								while (strpos($candidate, '/../') !== false) {
									$candidate = preg_replace('#/[^/]+/\.\.#', '', $candidate, 1);
								}
								$real = str_replace('\\', '/', realpath($candidate) ?: $candidate);
								if (is_file($real)) {
									$resolved = $real;
									break;
								}
							}
							// Fallback: resolve relative to app root
							if ($resolved === null) {
								$candidate = $app_root . '/' . $rel;
								while (strpos($candidate, '/../') !== false) {
									$candidate = preg_replace('#/[^/]+/\.\.#', '', $candidate, 1);
								}
								$real = str_replace('\\', '/', realpath($candidate) ?: $candidate);
								if (is_file($real)) {
									$resolved = $real;
								}
							}
							if ($resolved === null) {
								return $m[0];
							}
							// Embed as data-URI for maximum PDF compatibility
							$ext = strtolower(pathinfo($resolved, PATHINFO_EXTENSION));
							$mimeMap = ['svg' => 'image/svg+xml', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'webp' => 'image/webp'];
							$mime = $mimeMap[$ext] ?? 'application/octet-stream';
							$data = @file_get_contents($resolved);
							if ($data !== false) {
								$base64 = base64_encode($data);
								return 'url("data:' . $mime . ';base64,' . $base64 . '")';
							}
							return 'url("' . $resolved . '")';
						},
						$html_to_pdf
					);
				}

				$format = $sysDefaults['pdfpapersize'] ?? 'A4';
				$mpdf = new \Mpdf\Mpdf([
					'mode' => 'utf-8',
					'format' => $format,
					'orientation' => 'P',
					'margin_left'   => (float) ($sysDefaults['pdfleftmargin'] ?? 15),
					'margin_right'  => (float) ($sysDefaults['pdfrightmargin'] ?? 15),
					'margin_top'    => (float) ($sysDefaults['pdftopmargin'] ?? 15),
					'margin_bottom' => (float) ($sysDefaults['pdfbottommargin'] ?? 15),
					'useSubstitutions' => true,
				]);

				$mpdf->WriteHTML($html_to_pdf);

				$filename = $pdfname . '.pdf';
				if ($file_location === "inline") {
					$mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
				} elseif ($file_location === "download") {
					$mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
				} else {
					$mpdf->Output($filename, \Mpdf\Output\Destination::FILE);
				}

			} catch (\Mpdf\MpdfException $e) {
				error_log('mPDF Error: ' . $e->getMessage());
				throw new Exception('PDF generation failed: ' . $e->getMessage());
			}
		}
	}

	//echo "location: ".$file_location;
	convert_to_pdf($html, $pdfname, $file_location);

}

// ------------------------------------------------------------------------------
function getNumberOfDonePatches() {

	if (!checkTableExists(TB_PREFIX . 'sql_patchmanager')) {
		return 0;
	}

	$check_patches_sql = "SELECT max(sql_patch_ref) AS count FROM ".TB_PREFIX."sql_patchmanager ";
	$sth = dbQuery($check_patches_sql);

	$patches = $sth->fetch();

	//Returns number of patches applied
	$count = is_array($patches) ? ($patches['count'] ?? null) : null;

	return ($count === null || $count === '') ? 0 : (int) $count;
}

// ------------------------------------------------------------------------------
function getNumberOfPatches() {
	global $patch;
	#Max patches applied - start

	$patches = getNumberOfDonePatches();
	//$patch_count = count($patch);
	$patch_count = max( array_keys( $patch ) );
	//Returns number of patches to be applied
	return $patch_count - $patches;
}

// ------------------------------------------------------------------------------
function runPatches() {
	global $patch;
	global $db_server;
	global $dbh;
	#DEFINE SQL PATCH

	$display_block = "";

	if ($db_server == 'pgsql') {
		$sql = "SELECT 1 FROM pg_tables WHERE schemaname = current_schema() AND tablename ='".TB_PREFIX."sql_patchmanager'";
	} elseif ($db_server == 'sqlite') {
		$sql = "SELECT 1 FROM sqlite_master WHERE type='table' AND name='".TB_PREFIX."sql_patchmanager'";
	} else {
		$sql = "SHOW TABLES LIKE '".TB_PREFIX."sql_patchmanager'";
	}
	$sth = dbQuery($sql);
	$rows = $sth->fetchAll();

	$patch_page=array();	

	if(count($rows) == 1) {

		if ($db_server == 'pgsql') {
			// Yay!  Transactional DDL
			$dbh->beginTransaction();
		}
		for($i=0;$i < count($patch);$i++) {
//			run_sql_patch($i,$patch[$i]); // use instead of following line if patch application status display is to be suppressed
			$patch_page['rows'][$i] = run_sql_patch($i,$patch[$i]);
		}
		if ($db_server == 'pgsql') {
			// Yay!  Transactional DDL
			$dbh->commit();
		}

		// Keep only the rows that were actually applied (not already-skipped ones)
		$applied = array_values(array_filter($patch_page['rows'], function($r) {
			return isset($r['result']) && $r['result'] === 'done';
		}));
		$patch_page['rows']    = array_reverse($applied);
		$patch_page['applied_count'] = count($applied);
		$patch_page['mode']    = 'run';
		$patch_page['refresh'] = 5;
	}
	else {

		$patch_page['mode'] = 'init';
		$patch_page['init_log'] = initialise_sql_patch();

	}

	global $bladeView;
	$bladeView-> assign("page",$patch_page);

}

// ------------------------------------------------------------------------------
function backupStep() {
	global $patch;
	$pending = 0;
	for ($p = 0; $p < count($patch); $p++) {
		if (!check_sql_patch($p, $patch[$p]['name'])) {
			$pending++;
		}
	}
	$patch_page = array(
		'mode'          => 'backup',
		'pending_count' => $pending,
	);
	global $bladeView;
	$bladeView->assign("page", $patch_page);
}

// ------------------------------------------------------------------------------
function donePatches() {
	$patch_page['mode']    = 'done';
	$patch_page['refresh'] = 3;
	global $bladeView;
	$bladeView->assign("page", $patch_page);
}

// ------------------------------------------------------------------------------
function listPatches() {
	global $patch;

	$pending = array();
	for ($p = 0; $p < count($patch); $p++) {
		if (!check_sql_patch($p, $patch[$p]['name'])) {
			$pending[] = array(
				'id'   => $p,
				'name' => htmlsafe($patch[$p]['name']),
				'date' => htmlsafe($patch[$p]['date']),
			);
		}
	}

	// Show highest patch ID first
	$pending = array_reverse($pending);

	$patch_page = array(
		'mode'          => 'list',
		'pending_count' => count($pending),
		'rows'          => $pending,
	);

	global $bladeView;
	$bladeView->assign("page", $patch_page);
}

// ------------------------------------------------------------------------------
function check_sql_patch($check_sql_patch_ref, $check_sql_patch_field) {
   	$sql = "SELECT * FROM ".TB_PREFIX."sql_patchmanager WHERE sql_patch_ref = :patch" ;
	$sth = dbQuery($sql, ':patch', $check_sql_patch_ref);

	if(count($sth->fetchAll()) > 0) {
		return true;
	}
	return false;
}

// ------------------------------------------------------------------------------
/**
 * SQL patch 338: add denormalised list columns to si_invoices (see invoice_denorm).
 */
function si_patch338_invoice_denorm_columns(): void {
	global $db_server;
	$t = TB_PREFIX . 'invoices';
	if ($db_server === 'mysql') {
		$defs = [
			'denorm_invoice_total'           => 'DECIMAL(25,6) NOT NULL DEFAULT 0',
			'denorm_amount_paid'             => 'DECIMAL(25,6) NOT NULL DEFAULT 0',
			'denorm_amount_owing'            => 'DECIMAL(25,6) NOT NULL DEFAULT 0',
			'denorm_biller_name'             => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_customer_name'           => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_index_name'              => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_preference_description'  => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_preference_status'       => 'SMALLINT NOT NULL DEFAULT 0',
		];
		$add = [];
		foreach ($defs as $col => $def) {
			if (!checkFieldExists($t, $col)) {
				$add[] = 'ADD COLUMN `' . $col . '` ' . $def;
			}
		}
		if ($add !== []) {
			dbQuery('ALTER TABLE `' . $t . '` ' . implode(', ', $add));
		}
		return;
	}
	if ($db_server === 'pgsql') {
		$defs = [
			'denorm_invoice_total'           => 'NUMERIC(25,6) NOT NULL DEFAULT 0',
			'denorm_amount_paid'              => 'NUMERIC(25,6) NOT NULL DEFAULT 0',
			'denorm_amount_owing'             => 'NUMERIC(25,6) NOT NULL DEFAULT 0',
			'denorm_biller_name'             => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_customer_name'           => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_index_name'              => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_preference_description'  => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_preference_status'       => 'SMALLINT NOT NULL DEFAULT 0',
		];
		foreach ($defs as $col => $def) {
			dbQuery("ALTER TABLE {$t} ADD COLUMN IF NOT EXISTS {$col} {$def}");
		}
		return;
	}
	$sqliteCols = [
		'denorm_invoice_total'           => 'REAL NOT NULL DEFAULT 0',
		'denorm_amount_paid'             => 'REAL NOT NULL DEFAULT 0',
		'denorm_amount_owing'            => 'REAL NOT NULL DEFAULT 0',
		'denorm_biller_name'             => 'TEXT NOT NULL DEFAULT \'\'',
		'denorm_customer_name'           => 'TEXT NOT NULL DEFAULT \'\'',
		'denorm_index_name'              => 'TEXT NOT NULL DEFAULT \'\'',
		'denorm_preference_description'  => 'TEXT NOT NULL DEFAULT \'\'',
		'denorm_preference_status'       => 'INTEGER NOT NULL DEFAULT 0',
	];
	foreach ($sqliteCols as $col => $def) {
		if (!checkFieldExists($t, $col)) {
			dbQuery("ALTER TABLE {$t} ADD COLUMN {$col} {$def}");
		}
	}
}

/**
 * SQL patch 339: denormalised display strings on si_payment for the payments grid.
 */
function si_patch339_payment_denorm_columns(): void {
	global $db_server;
	$t = TB_PREFIX . 'payment';
	if ($db_server === 'mysql') {
		$defs = [
			'denorm_invoice_index_name' => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_biller_name'         => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
			'denorm_customer_name'       => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
		];
		$add = [];
		foreach ($defs as $col => $def) {
			if (!checkFieldExists($t, $col)) {
				$add[] = 'ADD COLUMN `' . $col . '` ' . $def;
			}
		}
		if ($add !== []) {
			dbQuery('ALTER TABLE `' . $t . '` ' . implode(', ', $add));
		}
		return;
	}
	if ($db_server === 'pgsql') {
		dbQuery("ALTER TABLE {$t} ADD COLUMN IF NOT EXISTS denorm_invoice_index_name VARCHAR(255) NOT NULL DEFAULT ''");
		dbQuery("ALTER TABLE {$t} ADD COLUMN IF NOT EXISTS denorm_biller_name VARCHAR(255) NOT NULL DEFAULT ''");
		dbQuery("ALTER TABLE {$t} ADD COLUMN IF NOT EXISTS denorm_customer_name VARCHAR(255) NOT NULL DEFAULT ''");
		return;
	}
	$sqliteCols = [
		'denorm_invoice_index_name' => 'TEXT NOT NULL DEFAULT \'\'',
		'denorm_biller_name'         => 'TEXT NOT NULL DEFAULT \'\'',
		'denorm_customer_name'       => 'TEXT NOT NULL DEFAULT \'\'',
	];
	foreach ($sqliteCols as $col => $def) {
		if (!checkFieldExists($t, $col)) {
			dbQuery("ALTER TABLE {$t} ADD COLUMN {$col} {$def}");
		}
	}
}

/**
 * SQL patch 379: denormalised invoice currency on si_payment (grid/reports; same as invoice).
 */
function si_patch379_payment_currency_denorm_columns(): void {
	global $db_server;
	$t   = TB_PREFIX . 'payment';
	$inv = TB_PREFIX . 'invoices';
	if ($db_server === 'mysql') {
		$defs = [
			'denorm_currency_sign' => 'VARCHAR(50) NOT NULL DEFAULT \'\'',
			'denorm_currency_code' => 'VARCHAR(25) NOT NULL DEFAULT \'\'',
		];
		$add = [];
		foreach ($defs as $col => $def) {
			if (!checkFieldExists($t, $col)) {
				$add[] = 'ADD COLUMN `' . $col . '` ' . $def;
			}
		}
		if ($add !== []) {
			dbQuery('ALTER TABLE `' . $t . '` ' . implode(', ', $add));
		}
		if (checkFieldExists($t, 'denorm_currency_sign') && checkFieldExists($inv, 'currency_sign')) {
			dbQuery(
				'UPDATE `' . $t . '` p INNER JOIN `' . $inv . '` iv ON (p.ac_inv_id = iv.id AND p.domain_id = iv.domain_id) '
				. 'SET p.denorm_currency_sign = COALESCE(iv.currency_sign, \'\'), p.denorm_currency_code = COALESCE(iv.denorm_currency_code, \'\')'
			);
		}
		return;
	}
	if ($db_server === 'pgsql') {
		dbQuery("ALTER TABLE {$t} ADD COLUMN IF NOT EXISTS denorm_currency_sign VARCHAR(50) NOT NULL DEFAULT ''");
		dbQuery("ALTER TABLE {$t} ADD COLUMN IF NOT EXISTS denorm_currency_code VARCHAR(25) NOT NULL DEFAULT ''");
		if (checkFieldExists($inv, 'currency_sign')) {
			dbQuery(
				"UPDATE {$t} p SET denorm_currency_sign = COALESCE(iv.currency_sign, ''), denorm_currency_code = COALESCE(iv.denorm_currency_code, '') "
				. "FROM {$inv} iv WHERE p.ac_inv_id = iv.id AND p.domain_id = iv.domain_id"
			);
		}
		return;
	}
	$sqliteCols = [
		'denorm_currency_sign' => 'TEXT NOT NULL DEFAULT \'\'',
		'denorm_currency_code' => 'TEXT NOT NULL DEFAULT \'\'',
	];
	foreach ($sqliteCols as $col => $def) {
		if (!checkFieldExists($t, $col)) {
			dbQuery("ALTER TABLE {$t} ADD COLUMN {$col} {$def}");
		}
	}
	if (checkFieldExists($inv, 'currency_sign')) {
		dbQuery(
			'UPDATE ' . $t . ' AS p SET denorm_currency_sign = COALESCE((SELECT iv.currency_sign FROM ' . $inv
			. ' iv WHERE iv.id = p.ac_inv_id AND iv.domain_id = p.domain_id), \'\')'
		);
		dbQuery(
			'UPDATE ' . $t . ' AS p SET denorm_currency_code = COALESCE((SELECT iv.denorm_currency_code FROM ' . $inv
			. ' iv WHERE iv.id = p.ac_inv_id AND iv.domain_id = p.domain_id), \'\')'
		);
	}
}

/**
 * SQL patch 340: populate denormalised columns for all invoices (all domains).
 */
function si_patch340_backfill_invoice_denorm(): void {
	$sth = dbQuery('SELECT DISTINCT domain_id FROM ' . TB_PREFIX . 'invoices');
	$domains = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
	foreach ($domains as $did) {
		invoice_denorm::rebuildDomain((int) $did);
	}
}

function si_patch379_backfill_preference_currency_id(): void {
	require_once __DIR__ . '/class/siCurrencies.php';
	require_once __DIR__ . '/class/CurrencySignHelper.php';

	$rows = dbQuery('SELECT pref_id, domain_id, pref_currency_sign FROM ' . TB_PREFIX . 'preferences
		WHERE currency_id IS NULL AND pref_currency_sign IS NOT NULL AND pref_currency_sign != \'\'')
		->fetchAll(PDO::FETCH_ASSOC);

	if (empty($rows)) {
		return;
	}

	$domainIds = array_unique(array_map('intval', array_column($rows, 'domain_id')));
	foreach ($domainIds as $did) {
		siCurrencies::seedDefaults($did);
	}

	foreach ($rows as $row) {
		$domainId = (int) $row['domain_id'];
		$sign     = CurrencySignHelper::forDisplay($row['pref_currency_sign']);
		if ($sign === '') {
			continue;
		}

		$currRow = siCurrencies::findBySign($domainId, $sign)
			?: siCurrencies::findOrCreate($domainId, $sign);

		if ($currRow && !empty($currRow['id'])) {
			dbQuery('UPDATE ' . TB_PREFIX . 'preferences SET currency_id = :cid
				WHERE pref_id = :pid AND domain_id = :did',
				':cid', (int) $currRow['id'],
				':pid', (int) $row['pref_id'],
				':did', $domainId);
		}
	}
}

/**
 * SQL patch 341: secondary indexes on si_invoices for domain-scoped lists, charts,
 * and filters that use denorm_* (manage grid sort, customer/biller scoping, owing buckets).
 */
function si_patch341_invoice_denorm_indexes(): void {
	global $db_server;
	$t = TB_PREFIX . 'invoices';
	if ($db_server === 'mysql') {
		$indexes = [
			'si_inv_dom_cust'       => '(`domain_id`, `customer_id`)',
			'si_inv_dom_biller'     => '(`domain_id`, `biller_id`)',
			'si_inv_dom_idxid'      => '(`domain_id`, `index_id`)',
			'si_inv_dom_pstat_owing' => '(`domain_id`, `denorm_preference_status`, `denorm_amount_owing`)',
		];
		foreach ($indexes as $name => $cols) {
			if (! checkMysqlIndexExists($t, $name)) {
				dbQuery("ALTER TABLE `{$t}` ADD KEY `{$name}` {$cols}");
			}
		}
		return;
	}
	if ($db_server === 'pgsql') {
		$stmts = [
			"CREATE INDEX IF NOT EXISTS si_inv_dom_cust ON {$t} (domain_id, customer_id)",
			"CREATE INDEX IF NOT EXISTS si_inv_dom_biller ON {$t} (domain_id, biller_id)",
			"CREATE INDEX IF NOT EXISTS si_inv_dom_idxid ON {$t} (domain_id, index_id)",
			"CREATE INDEX IF NOT EXISTS si_inv_dom_pstat_owing ON {$t} (domain_id, denorm_preference_status, denorm_amount_owing)",
		];
		foreach ($stmts as $sql) {
			dbQuery($sql);
		}
		return;
	}
	$stmts = [
		"CREATE INDEX IF NOT EXISTS si_inv_dom_cust ON {$t} (domain_id, customer_id)",
		"CREATE INDEX IF NOT EXISTS si_inv_dom_biller ON {$t} (domain_id, biller_id)",
		"CREATE INDEX IF NOT EXISTS si_inv_dom_idxid ON {$t} (domain_id, index_id)",
		"CREATE INDEX IF NOT EXISTS si_inv_dom_pstat_owing ON {$t} (domain_id, denorm_preference_status, denorm_amount_owing)",
	];
	foreach ($stmts as $sql) {
		dbQuery($sql);
	}
}

// ------------------------------------------------------------------------------
/**
 * SQLite cannot DROP the table-level UNIQUE(email) via a portable one-liner.
 * Rebuild si_user without that constraint, preserving data and auth columns.
 */
function si_sqlite_patch335_rebuild_si_user(): void {
	global $dbh;
	$t    = TB_PREFIX . 'user';
	$tmp  = $t . '__si_auth_rebuild';
	$sqls = array(
		"CREATE TABLE {$tmp} (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			email TEXT DEFAULT NULL,
			name TEXT DEFAULT NULL,
			role_id INTEGER DEFAULT NULL,
			domain_id INTEGER NOT NULL DEFAULT 0,
			password TEXT DEFAULT NULL,
			enabled INTEGER NOT NULL DEFAULT 1,
			user_id INTEGER NOT NULL DEFAULT 0,
			auth_staff_email TEXT DEFAULT NULL,
			auth_customer_key TEXT DEFAULT NULL
		)",
		"INSERT INTO {$tmp} SELECT id, email, name, role_id, domain_id, password, enabled, user_id, auth_staff_email, auth_customer_key FROM {$t}",
		"DROP TABLE {$t}",
		"ALTER TABLE {$tmp} RENAME TO {$t}",
		'CREATE UNIQUE INDEX IF NOT EXISTS si_user_pk ON ' . $t . ' (domain_id, id)',
	);
	foreach ($sqls as $sql) {
		$dbh->exec($sql);
	}
	$maxStmt = $dbh->query('SELECT COALESCE(MAX(id), 0) FROM ' . $t);
	$max     = $maxStmt ? (int) $maxStmt->fetchColumn() : 0;
	if ($max > 0) {
		$dbh->exec("DELETE FROM sqlite_sequence WHERE name = " . $dbh->quote($t));
		$dbh->exec('INSERT INTO sqlite_sequence (name, seq) VALUES (' . $dbh->quote($t) . ', ' . $max . ')');
	}
}

// ------------------------------------------------------------------------------
function run_sql_patch($id, $patch) {
	global $dbh;
	global $db_server;
	$display_block = "";

	$sql = "SELECT * FROM ".TB_PREFIX."sql_patchmanager WHERE sql_patch_ref = :id" ;
	$sth = dbQuery($sql, ':id', $id);

	$escaped_id = htmlsafe($id);
	$patch_name = htmlsafe($patch['name']);
	#forget about the patch as it has already been run!!

	$patch_row=array();

	if (count($sth->fetchAll()) != 0)  {

		$patch_row['id']		= $escaped_id;
		$patch_row['name']		= $patch_name;
		$patch_row['text']		= "Skipping SQL patch $escaped_id, $patch_name as it <i>has</i> already been applied";
		$patch_row['result']	= "skip";
	}
	else {

		//patch hasn't been run
		#so run the patch
		if ((int) $id === 335 && $db_server === 'sqlite') {
			si_sqlite_patch335_rebuild_si_user();
		} elseif ((int) $id === 356) {
			si_patch338_invoice_denorm_columns();
		} elseif ((int) $id === 357) {
			si_patch339_payment_denorm_columns();
		} elseif ((int) $id === 358) {
			si_patch340_backfill_invoice_denorm();
		} elseif ((int) $id === 359) {
			si_patch341_invoice_denorm_indexes();
		} elseif ((int) $id === 366) {
			si_patch379_payment_currency_denorm_columns();
		} elseif ((int) $id === 379) {
			si_patch379_backfill_preference_currency_id();
		} elseif ((int) $id === 360) {
			require_once __DIR__ . '/global_app_settings.php';
			si_patch342_global_config();
		} else {
			dbQuery($patch['patch']);
		}

		$patch_row['id']		= $escaped_id;
		$patch_row['name']		= $patch_name;
		$patch_row['text']		= "SQL patch $escaped_id, $patch_name <i>has</i> been applied to the database";
		$patch_row['result']	= "done";

		# now update the ".TB_PREFIX."sql_patchmanager table		
		$sql_update = "INSERT INTO ".TB_PREFIX."sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)";		
		dbQuery($sql_update, ':id', $id, ':name', $patch['name'], ':date', $patch['date'], ':patch', $patch['patch']);

		if($id == 126) {
			patch126();
		} 

		/*
		 * cusom_fields to new customFields patch - commented out till future
		 */
			/*
		 	elseif($id == 137) {
				convertInitCustomFields();
			}
			*/
		
	}
	return $patch_row;
}

// ------------------------------------------------------------------------------
function initialise_sql_patch() {
	global $db_server;

	// Build a portable CREATE TABLE for the patch manager
	if ($db_server == 'pgsql') {
		$sql_patch_init = "CREATE TABLE IF NOT EXISTS ".TB_PREFIX."sql_patchmanager (
			sql_id SERIAL PRIMARY KEY,
			sql_patch_ref VARCHAR(50) NOT NULL,
			sql_patch VARCHAR(255) NOT NULL,
			sql_release VARCHAR(25) NOT NULL,
			sql_statement TEXT NOT NULL
		)";
	} elseif ($db_server == 'sqlite') {
		$sql_patch_init = "CREATE TABLE IF NOT EXISTS ".TB_PREFIX."sql_patchmanager (
			sql_id INTEGER PRIMARY KEY AUTOINCREMENT,
			sql_patch_ref VARCHAR(50) NOT NULL,
			sql_patch VARCHAR(255) NOT NULL,
			sql_release VARCHAR(25) NOT NULL,
			sql_statement TEXT NOT NULL
		)";
	} else {
		// MySQL
		$sql_patch_init = "CREATE TABLE IF NOT EXISTS ".TB_PREFIX."sql_patchmanager (
			sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			sql_patch_ref VARCHAR(50) NOT NULL,
			sql_patch VARCHAR(255) NOT NULL,
			sql_release VARCHAR(25) NOT NULL,
			sql_statement TEXT NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	}
	dbQuery($sql_patch_init);

	$log = "Step 2 - The SQL patch table has been created<br />";

	if ($db_server == 'mysql') {
		$sql_insert = "INSERT INTO ".TB_PREFIX."sql_patchmanager
			(sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement)
			VALUES (NULL, '1', 'Create ".TB_PREFIX."sql_patchmanager table', '20060514', :patch)";
	} else {
		$sql_insert = "INSERT INTO ".TB_PREFIX."sql_patchmanager
			(sql_patch_ref, sql_patch, sql_release, sql_statement)
			VALUES ('1', 'Create ".TB_PREFIX."sql_patchmanager table', '20060514', :patch)";
	}
	dbQuery($sql_insert, ':patch', $sql_patch_init);

	$log .= "Step 3 - The SQL patch has been inserted into the SQL patch table<br />";

	return $log;
}

// ------------------------------------------------------------------------------
/**
 * Mark every patch in the $patch array as applied in sql_patchmanager.
 *
 * Called after a fresh install from structure.sql so the patch-runner page
 * never appears on first login. The schema created by structure.sql already
 * reflects the latest state, so historical migration patches must not re-run.
 */
function install_mark_all_patches_done() {
	global $patch, $db_server;

	if (empty($patch)) {
		return;
	}

	foreach ($patch as $id => $p) {
		if (check_sql_patch($id, $p['name'])) {
			continue; // already recorded
		}
		if ($db_server == 'mysql') {
			$sql = "INSERT INTO ".TB_PREFIX."sql_patchmanager
				(sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement)
				VALUES (NULL, :ref, :name, :date, :sql)";
		} else {
			$sql = "INSERT INTO ".TB_PREFIX."sql_patchmanager
				(sql_patch_ref, sql_patch, sql_release, sql_statement)
				VALUES (:ref, :name, :date, :sql)";
		}
		dbQuery($sql,
			':ref',  $id,
			':name', $p['name'],
			':date', $p['date'],
			':sql',  $p['patch']
		);
	}
}

// ------------------------------------------------------------------------------
function patch126() {
	//SC: MySQL-only function, not porting to PostgreSQL
	$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE product_id = 0";
	$sth = dbQuery($sql);

	while($res = $sth->fetch()) {
		$sql = "INSERT INTO ".TB_PREFIX."products (id, description, unit_price, enabled, visible) 
			VALUES (NULL, :description, :gross_total, '0',  '0')";
		dbQuery($sql, ':description', $res['description'], ':total', $res['gross_total']);
		$id = lastInsertId();

		$sql = "UPDATE ".TB_PREFIX."invoice_items SET product_id = :id, unit_price = :price WHERE id = :item AND domain_id = :domain_id";

		dbQuery($sql,
			':id', $id[0],
			':price', $res['gross_total'],
			':item', $res['id'],
			':domain_id', $res['domain_id']
			);
	}
}

// ------------------------------------------------------------------------------
function convertInitCustomFields() {
	/* check if any value set -> keeps all data for sure */
	global $dbh;
    $domain_id = domain_id::get();

	$sql = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE domain_id = :domain_id";
	$sth = $dbh->prepare($sql, ':domain_id', $domain_id);
	$sth->execute();

	while($custom = $sth->fetch()) {
		if(preg_match("/(.+)_cf([1-4])/",$custom['cf_custom_field'],$match)) {

			switch($match[1]) {
				case "biller": $cat = 1;	break;
				case "customer": $cat = 2;	break;
				case "product": $cat = 3;	break;
				case "invoice": $cat = 4;	break;
				default: $case = 0;
			}

			$cf_field = "custom_field".$match[2];
			$sql = "SELECT id, :field FROM :table WHERE domain_id = :domain_id";
			$tablename = TB_PREFIX.$match[1];
			// Only biller table is singular, products, invoices and customers tables are all plural
			if($match[1] != "biller") {
				$tablename .= "s";
			}

			$store = false;

			/*
			 * If custom field name is set
			 */
			if($custom['cf_custom_label'] != NULL) {
				$store = true;
			}

			//error_log($sql);
			$tth = $dbh->prepare($sql);
			$tth->bindValue(':table', $tablename);
			$tth->bindValue(':field', $cf_field);
			$tth->bindValue(':domain_id', $domain_id);
			$tth->execute();

			/*
			 * If any field is set, create custom field
			 */
			while($res = $tth->fetch()) {
				if($res[1] != NULL) {
					$store = true;
					break;
				}
			}

			if($store) {

				//create new text custom field
				saveInitCustomField(3,$cat,$custom['cf_custom_field'],$custom['cf_custom_label']);
				$id = lastInsertId();
				error_log($id);

				$plugin = getPluginById(3);
				$plugin->setFieldId($id);

				//insert all data
				$uth = $dbh->prepare($sql);
				$uth->bindValue(':table', $tablename);
				$uth->bindValue(':field', $cf_field);
				$uth->bindValue(':domain_id', $domain_id);
				$uth->execute();
				while($res2 = $uth->fetch()) {
					$plugin->saveInput($res2[$cf_field], $res2['id']);
				}
			}
		}
	}
}

// ------------------------------------------------------------------------------
function saveInitCustomField($id, $category, $name, $description) {
// This function is exactly same as saveCustomField() in ./include/manageCustomFields.php but without the final echo output
	$sql = "INSERT INTO ".TB_PREFIX."custom_fields  (pluginId, categorieId, name, description) 
		VALUES (:id, :category, :name, :description)";
	dbQuery($sql, ':id', $id, ':category', $category, ':name', $name, ':description', $description);
//	echo "SAVED<br />";
}

// start of db query functions moved from functions.php on 2013-10-28

/**
* Function: get_custom_field_label
* 
* Prints the name of the custom field based on the input. If the custom field has not been defined by the user than use the default in the lang files
*
* Arguments:
* field		- The custom field in question
**/
function get_custom_field_label($field, $domain_id='')         {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

    $sql =  "SELECT cf_custom_label FROM ".TB_PREFIX."custom_fields WHERE cf_custom_field = :field AND domain_id = :domain_id";
    $sth = dbQuery($sql, ':field', $field, ':domain_id', $domain_id);

    $cf = $sth->fetch();

    //grab the last character of the field variable
    $get_cf_number = $field[strlen($field)-1];    

    //if custom field is blank in db use the one from the LANG files
    if ($cf['cf_custom_label'] == null) {
       	$cf['cf_custom_label'] = $LANG['custom_field'] . $get_cf_number;
    }

    return $cf['cf_custom_label'];
}

function calc_invoice_paid($inv_idField, $domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	#amount paid calc - start
	$x1 = "SELECT COALESCE(SUM(ac_amount), 0) AS amount FROM ".TB_PREFIX."payment WHERE ac_inv_id = :inv_id AND domain_id = :domain_id";
	$sth = dbQuery($x1, ':inv_id', $inv_idField, ':domain_id',$domain_id);
	while ($result_x1Array = $sth->fetch()) {
		$invoice_paid_Field = $result_x1Array['amount'];
		$invoice_paid_Field_format = number_format($result_x1Array['amount'],2);
		#amount paid calc - end
		return $invoice_paid_Field;
	}
}

function calc_customer_total($customer_id, $domain_id='', $isReal=false) {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$real1 = '';
	$real2 = '';
	if ($isReal) {
		$real1 = " LEFT JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)";
		$real2 = " AND pr.status = 1";
	}

    $sql ="SELECT
		COALESCE(SUM(ii.total),  0) AS total 
	FROM
		".TB_PREFIX."invoice_items ii INNER JOIN
		".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
		$real1
	WHERE  
		iv.customer_id  = :customer
	AND ii.domain_id = :domain_id
	$real2";

    $sth = dbQuery($sql, ':customer', $customer_id, ':domain_id',$domain_id);
	$invoice = $sth->fetch();

	//return number_format($invoice['total'],"#########.##");
	return $invoice['total'];
}

function calc_customer_paid($customer_id, $domain_id='', $isReal=false) {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	$real1 = '';
	$real2 = '';
	if ($isReal) {
		$real1 = " LEFT JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)";
		$real2 = " AND pr.status = 1";
	}

#amount paid calc - start
	$sql = "
	SELECT COALESCE(SUM(ap.ac_amount), 0) AS amount 
	FROM
		".TB_PREFIX."payment ap INNER JOIN
		".TB_PREFIX."invoices iv ON (iv.id = ap.ac_inv_id AND iv.domain_id = ap.domain_id)
		$real1
	WHERE 
		iv.customer_id = :customer
	AND ap.domain_id = :domain_id
	$real2";

	$sth = dbQuery($sql, ':customer', $customer_id, ':domain_id',$domain_id);
	$invoice = $sth->fetch();

	return $invoice['amount'];
}

/**
* Function: calc_invoice_tax
* 
* Calculates the total tax for a given invoices
*
* Arguments:
* invoice_id		- The name of the field, ie. Custom Field 1, etc..
**/
function calc_invoice_tax($invoice_id, $domain_id='') {
	global $LANG;
	$domain_id = domain_id::get($domain_id);

	#invoice total tax
	$sql ="SELECT SUM(tax_amount) AS total_tax FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :invoice_id AND domain_id = :domain_id";
	$sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id',$domain_id);

	$tax = $sth->fetch();
	if ($tax === false) {
		return null;
	}

	return $tax['total_tax'];
}

/**
* Function: show_custom_field
* 
* If a custom field has been defined then show it in the add,edit, or view invoice screen. This is used for the Invoice Custom Fields - may be used for the others as wll based on the situation
*
* Parameters:
* custom_field		- the db name of the custom field ie invoice_cf1
* custom_field_value	- the value of this custom field for a given invoice
* permission		- the permission level - ie. in a print view its gets a read level, in an edit or add screen its write leve
* css_class_tr		- the css class the the table row (tr)
* css_class1		- the css class of the first td
* css_class2		- the css class of the second td
* td_col_span		- the column span of the right td
* seperator		- used in the print view ie. adding a : between the 2 values
*
* Returns:
* Depending on the permission passed, either a formatted input box and the label of the custom field or a table row and data
**/

function show_custom_field($custom_field,$custom_field_value,$permission,$css_class_tr,$css_class1,$css_class2,$td_col_span,$seperator) {

	$domain_id = domain_id::get();

	# get the last character of the $custom field - used to set the name of the field

	$custom_field_number =  substr($custom_field, -1, 1);

	#get the label for the custom field

	$display_block = "";

	$get_custom_label ="SELECT cf_custom_label FROM ".TB_PREFIX."custom_fields WHERE cf_custom_field = :field AND domain_id = :domain_id";
	$sth = dbQuery($get_custom_label, ':field', $custom_field, ':domain_id', $domain_id);

	while ($Array_cl = $sth->fetch()) {
                $has_custom_label_value = $Array_cl['cf_custom_label'];
	}
	/*if permision is write then coming from a new invoice screen show show only the custom field and have a label
	* if custom_field_value !null coming from existing invoice so show only the cf that they actually have
	*/	
	if ( (($has_custom_label_value != null) AND ( $permission == "write")) OR ($custom_field_value != null)) {

		$custom_label_value = htmlsafe(get_custom_field_label($custom_field));

		if ($permission == "read") {
			$display_block = <<<EOD
			<div class="mb-2">
				<span class="text-secondary small">$custom_label_value$seperator</span>
				<div>$custom_field_value</div>
			</div>
EOD;
		}

		else if ($permission == "write") {
			$display_block = <<<EOD
			<div class="mb-3">
				<label class="form-label">$custom_label_value
					<a class="cluetip ms-1" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="Custom Fields"><i class="ti ti-help"></i></a>
				</label>
				<input type="text" name="customField$custom_field_number" value="$custom_field_value" class="form-control" />
			</div>
EOD;
		}
	}
	return $display_block;
}

// end of db query functions moved from functions.php on 2013-10-28
