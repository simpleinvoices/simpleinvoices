<?php

/**
* $log_dbh is used by dbLogger, but dbLogger is unused throughout the project 
if(LOGGING) {
	//Logging connection to prevent mysql_insert_id problems. Need to be called before the second connect...
	$log_dbh = db_connector();
}
*/

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
	$pdoAdapter = substr($config->resources->db->adapter, 4);

	if(!defined('PDO::MYSQL_ATTR_INIT_COMMAND') AND $pdoAdapter == "mysql" AND $config->resources->db->adapter->utf8 == true)
	{
        simpleInvoicesError("PDO::mysql_attr");
	}

	try
	{

		switch ($pdoAdapter)
		{

		    case "pgsql":
		    	$connlink = new PDO(
					$pdoAdapter.':host='.$config->resources->db->params->host.';	dbname='.$config->resources->db->params->dbname,	$config->resources->db->params->username, $config->resources->db->params->password
				);
		    	break;

		    case "sqlite":
		    	$connlink = new PDO(
					$pdoAdapter.':host='.$config->resources->db->params->host.';	dbname='.$config->resources->db->params->dbname,	$config->resources->db->params->username, $config->resources->db->params->password
				);
				break;

		    case "mysql":
                if ($config->resources->db->params->charset === 'utf8') {
                    $connlink = new PDO(
                        'mysql:host='.$config->resources->db->params->host.'; port='.$config->resources->db->params->port.'; dbname='.$config->resources->db->params->dbname, $config->resources->db->params->username, $config->resources->db->params->password,  array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
                    );
                } else {
                    $connlink = new PDO(
                        $pdoAdapter.':host='.$config->resources->db->params->host.'; port='.$config->resources->db->params->port.'; dbname='.$config->resources->db->params->dbname,    $config->resources->db->params->username, $config->resources->db->params->password
                    );
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
    global $errm;
	$argc = func_num_args();
	$binds = func_get_args();
    $sth = false;
    // PDO SQL Preparation
    $sth = $dbh->prepare($sqlQuery);
    if ($argc > 1) {
		array_shift($binds);

		// HTML Tags that are allowed in certain fields
		$allowed_tags = '<p><i><b><strong><div><ul><ol><li>';
		// Fields that can have HTML tags
		$fields = '/note|footer/';

		for ($i = 0; $i < count($binds); $i++) {
			/*
			 * Strip out tags that could be malicious
			 * If the HTML tags are allowed for this field, strip out any banned tags otherwise strip out all the tags
			 */
			// Set the current field
			$field = $binds[$i];

			if (preg_match($fields, $field)) {
				// htmlspecialchars_decode is needed here to enusre that tags can be found
				$val = strip_tags(htmlspecialchars_decode($binds[++$i]), $allowed_tags);

				// Encode any tags again before putting them back
		        $sth->bindValue($field, htmlspecialchars($val));
	        } else {
	        	$val = strip_tags(htmlspecialchars_decode($binds[++$i]));

	        	$sth->bindValue($field, htmlspecialchars($val));
	        }
		}
    }
    /*
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
    // 2010 aducom software - albert drent
    // if there's a database error like duplicate key than this error is not trapped by the try-catch
    // therefore the error is trapped here and the database message is put into the global $errm
    // for errordisplay.
    if ($sth->errorCode() !=0) {
       $errm = $sth->errorInfo();
       return null;
    }
    else {
     	return $sth;
    }
}

/* Not used
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

		// SC: Check for the patch manager patch loader.  If a
		//     patch is being run, avoid $log_dbh due to the
		//     risk of deadlock.
		//
		$call_stack = debug_backtrace();
		//SC: XXX Change the number back to 1 if returned to directly
		//    within dbQuery.  The joys of dealing with the call stack.

		if ($call_stack[2]['function'] == 'run_sql_patch') {
		// Running the patch manager, avoid deadlock 
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
*/

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
	$pdoAdapter = substr($config->resources->db->adapter, 4);

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


function getInvoice($id) {
	global $dbh;
	global $config;
	global $auth_session;
    
    $SI_INVOICES = new SimpleInvoices_Db_Table_Invoices();
    $SI_INVOICE_ITEMS = new SimpleInvoices_Db_Table_InvoiceItems();
    
    $invoice = $SI_INVOICES->getInvoice($id);

	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['date'] = siLocal::date( $invoice['date'] );
	$invoice['total'] = $SI_INVOICE_ITEMS->getInvoiceTotal($invoice['id']);
	$invoice['gross'] = invoice::getInvoiceGross($invoice['id']);
	$invoice['paid'] = calc_invoice_paid($invoice['id']);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];
    if (isset($invoice['inv_status'])) {
		// This seems to be a thing of the past.
		// I think we could delete the whole "if".
		$invoice['status'] = $invoice['inv_status'];
	}
	else {
		$invoice['status'] = '';
	}


	#invoice total tax
    $result = $SI_INVOICE_ITEMS->getTotals($id);
	
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
	global $dbh;

	$sql = "select
				DISTINCT tax.tax_id
			from ".TB_PREFIX."invoice_item_tax item_tax,
				 ".TB_PREFIX."invoice_items item,
				 ".TB_PREFIX."tax tax
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
	global $dbh;

	$sql = "select
				tax.tax_description as tax_name,
				sum(item_tax.tax_amount) as tax_amount,
				count(*) as count
			from
				".TB_PREFIX."invoice_item_tax item_tax,
				".TB_PREFIX."invoice_items item,
				".TB_PREFIX."tax tax
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
	global $dbh;

	$sql = "select
				item_tax.id as row_id,
				tax.tax_description as tax_name,
				tax.tax_id as tax_id
			from
				".TB_PREFIX."invoice_item_tax item_tax,
				".TB_PREFIX."tax tax
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

    $SI_INVOICE_ITEMS = new SimpleInvoices_Db_Table_InvoiceItems();
    
	if($invoice = $sth->fetch()) {

		$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['date'] ) );
		$invoice['date'] = siLocal::date($invoice['date']);

		#invoice total total - start
		$invoice['total'] = $SI_INVOICE_ITEMS->getInvoiceTotal($invoice['id']);
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
	global $LANG;

    $SI_CUSTOMERS = new SimpleInvoices_Db_Table_Customers();
    $result = $SI_CUSTOMERS->fetchAll();
	
	$customers = array();

    foreach($result as $customer) {
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
		$customers[] = $customer;
	}

	return $customers;
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

function insertInvoice($type) {
	global $dbh;
	global $db_server;
	global $auth_session;
    
    $SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();

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
    $pref_group= $SI_PREFERENCES->getPreferenceById($_POST[preference_id]);

	$sth= dbQuery($sql,
		#':index_id', index::next('invoice',$pref_group[index_group],$_POST[biller_id]),
		':index_id', index::next('invoice',$pref_group['index_group']),
		':domain_id', $auth_session->domain_id,
		':biller_id', $_POST['biller_id'],
		':customer_id', $_POST['customer_id'],
		':type', $type,
		':preference_id', $_POST['preference_id'],
		':date', $_POST['date'],
		':note', $_POST['note'],
		':customField1', $_POST['customField1'],
		':customField2', $_POST['customField2'],
		':customField3', $_POST['customField3'],
		':customField4', $_POST['customField4']
		);

    #index::increment('invoice',$pref_group[index_group],$_POST[biller_id]);
    index::increment('invoice',$pref_group['index_group']);

    return $sth;
}

function updateInvoice($invoice_id) {

    global $logger;
    $SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();
    
    $current_invoice = invoice::select($_POST['id']);
    $current_pref_group = $SI_PREFERENCES->getPreferenceById($current_invoice[preference_id]);

    $new_pref_group= $SI_PREFERENCES->getPreferenceById($_POST[preference_id]);

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
Function: invoice_item_tax
Purpose: insert/update the multiple taxes per line item into the si_invoice_item_tax table
*/
function invoice_item_tax($invoice_item_id,$line_item_tax_id,$unit_price,$quantity,$action="") {

	global $logger;

    $SI_TAX = new SimpleInvoices_Db_Table_Tax();
    
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
			$tax = $SI_TAX->getTaxRateById($value);

			$logger->log("ITEM :: Key: ".$key." Value: ".$value, Zend_Log::INFO);
			$logger->log('ITEM :: tax rate: '.$tax['tax_percentage'], Zend_Log::INFO);

			$tax_amount = lineItemTaxCalc($tax,$unit_price,$quantity);
			//get Total tax for line item
			$tax_total = $tax_total + $tax_amount;

			$logger->log('ITEM :: Qty: '.$quantity.' Unit price: '.$unit_price, Zend_Log::INFO);
			$logger->log('ITEM :: Tax rate: '.$tax['tax_percentage'].' Tax type: '.$tax['type'].' Tax $: '.$tax_amount, Zend_Log::INFO);

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
	global $logger;
	global $auth_session; //TODO add some domain_id stuff in here if requried

	$lctable = strtolower($module);
	$s_idField = ''; // Presetting the whitelisted column to fail

	/*
	 * SC: $valid_tables contains the base names of all tables that can
	 *     have rows deleted using this function.  This is used for
	 *     whitelisting deletion targets.
	 */
	$valid_tables = array('invoices', 'invoice_items', 'invoice_item_tax', 'products', 'cron');

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
		} elseif ($lctable == 'cron') {
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

?>