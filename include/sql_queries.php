<?php
$pdoAdapter = substr($config->database->adapter, 4);
if (LOGGING) {
    // Logging connection to prevent mysql_insert_id problems. Need to be called before the second connect...
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

/**
 * Establish the PDO connector to the database
 * @return PDO
 */
function db_connector() {
    global $config;
    global $databaseBuilt;
    global $pdoAdapter;

    if (!$databaseBuilt) return NULL;

    if (!defined('PDO::MYSQL_ATTR_INIT_COMMAND') && $pdoAdapter == "mysql" && $config->database->adapter->utf8 == true) {
        simpleInvoicesError("PDO::mysql_attr");
    }

    try {
        // @formatter:off
        switch ($pdoAdapter) {
            case "pgsql":
                $connlink = new PDO($pdoAdapter . ':host=' . $config->database->params->host .
                                               '; dbname=' . $config->database->params->dbname,
                                                             $config->database->params->username,
                                                             $config->database->params->password);
                break;

            case "sqlite":
                $connlink = new PDO($pdoAdapter . ':host=' . $config->database->params->host .
                                               '; dbname=' . $config->database->params->dbname,
                                                             $config->database->params->username,
                                                             $config->database->params->password);
                break;

            case "mysql":
                switch ($config->database->utf8) {
                    case true:
                        $connlink = new PDO('mysql:host='   . $config->database->params->host .
                                                '; port='   . $config->database->params->port .
                                                '; dbname=' . $config->database->params->dbname,
                                                              $config->database->params->username,
                                                              $config->database->params->password,
                                                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;"));
                        break;

                    case false:
                    default:
                        $connlink = new PDO($pdoAdapter . ':host='   . $config->database->params->host .
                                                         '; port='   . $config->database->params->port .
                                                         '; dbname=' . $config->database->params->dbname,
                                                                       $config->database->params->username,
                                                                       $config->database->params->password);
                        break;
                }
                break;
        }
        // @formatter:on
    } catch (PDOException $exception) {
        simpleInvoicesError("dbConnection", $exception->getMessage());
        die($exception->getMessage());
    }

    return $connlink;
}

/**
 * Replaces any parameter placeholders in a query with the value of that parameter.
 * @param string $query The sql query with parameter placeholders
 * @param array $params The array of substitution parameters
 * @return string The interpolated query
 */
function interpolateQuery($query, $params) {
    $keys = array();
    $values = $params;

    // build a regular expression for each parameter
    foreach ($params as $key => $value) {
        // Add quotes around the named parameters and ? parameters.
        if (is_string($key)) {
            $keys[] = '/' . $key . '/';
        } else {
            $keys[] = '/[?]/';
        }

        // If the value for this is is an array, make it a character separated string.
        if (is_array($value)) $values[$key] = implode(',', $value);
        // If the value is NULL, make it a string value of "NULL".
        if (is_null($value)) $values[$key] = 'NULL';
    }

    // Walk the array to see if we can add single-quotes to strings
    array_walk($values, create_function('&$v, $k', 'if (!is_numeric($v) && $v!="NULL") $v = "\'".$v."\'";'));
    $query = preg_replace($keys, $values, $query, 1, $count);
    return $query;
}

/**
 * Perform sql request.
 * Note: dbQuery is a variadic function that, in its simplest case,
 * functions as the old mysqlQuery does. The added complexity is
 * that it also handles named parameters (arguments) to the queries.
 *
 * Examples:
 * $sth = dbQuery('SELECT b.id, b.name FROM si_biller b WHERE b.enabled');
 * $tth = dbQuery('SELECT c.name FROM si_customers c WHERE c.id = :id',
 * ':id', $id);
 *
 * @param $sqlQuery Query to be performed.
 * @return Result of query.
 */
function dbQuery($sqlQuery) {
    global $dbh;
    global $databaseBuilt;

    if (!$databaseBuilt) return false;

    $argc = func_num_args();
    $binds = func_get_args();
    $sth = false;
    // PDO SQL Preparation
    $params = array();
    $sth = $dbh->prepare($sqlQuery);
    if ($argc > 1) {
        array_shift($binds);
        for ($i = 0; $i < count($binds); $i++) {
            $key = $binds[$i];
            $value = $binds[++$i];
            $params[$key] = $value;
            $sth->bindValue($key, $value);
        }
    }

    try {
        $sth->execute();
        dbLogger(interpolateQuery($sqlQuery, $params));
    } catch (Exception $e) {
        echo $e->getMessage();
        echo "dbQuery: Dude, what happened to your query?:<br /><br /> " . htmlsafe($sqlQuery) . "<br />" .
                         htmlsafe(end($sth->errorInfo()));
    }

    return $sth;
}

/**
 * Log database modification entries in the si_log table.
 * @param string $sqlQuery Query to be logged.
 * @param boolean $dump If true, the information will be written to
 *        the error log regardless of the transaction type. If false,
 *        the default, the normal logging is performed and only errors
 *        are written to the error log.
 */
function dbLogger($sqlQuery, $dump=false) {
    // For PDO it gives only the skeleton sql before merging with data
    global $log_dbh;
    global $dbh;
    global $auth_session;
    global $can_log;

    $userid = $auth_session->id;

    // Compact query to be logged
    $sqlQuery = preg_replace('/  +/', ' ', str_replace(PHP_EOL, '', $sqlQuery));
    if ($can_log && (preg_match('/^\s*select/iD', $sqlQuery) == 0) &&
                    (preg_match('/^\s*show\s*tables\s*like/iD', $sqlQuery) == 0)) {

        // Only log queries that could result in data/database modification
        $last = NULL;
        $tth = NULL;

        // @formatter:off
        $sql = "INSERT INTO " . TB_PREFIX . "log
                       (domain_id,
                        timestamp,
                        userid,
                        sqlquerie,
                        last_id)
                VALUES (?, CURRENT_TIMESTAMP , ?, ?, ?)";
        // @formatter:on

        // SC: Check for the patch manager patch loader. If a
        // patch is being run, avoid $log_dbh due to the
        // risk of deadlock.
        $call_stack = debug_backtrace();

        // SC: XXX Change the number back to 1 if returned to directly
        // within dbQuery. The joys of dealing with the call stack.
        if ($call_stack[2]['function'] == 'run_sql_patch') {
            // Running the patch manager, avoid deadlock
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

/**
 * Replace field/value pairs in a string.
 * @param string $str_in String to update.
 * @param array $fields This is an array with a key/value pairs to replace in the string.
 */
function replaceFields($str_in, $fields) {
    $str = $str_in;
    if (is_array($fields)) {
        foreach ($pairs as $old => $new) {
            $pattern = '/' . $old . '/';
            $replace = $new;
            $str = fieldReplace($str, $pattern, $replace);
        }
    }
    return $str;
}

/**
 * Retrieves the record ID of the most recently inserted row for the session.
 * Note: That the session is for the $dbh whose id was created by AUTO_INCREMENT
 * (MySQL) or a sequence (PostgreSQL). This is a convenience function to handle
 * the backend-specific details so you don't have to.
 * @return Record ID
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
    // echo $sql;
    $sth = $dbh->prepare($sql);
    $sth->execute();
    return $sth->fetchColumn();
}

/**
 * Load SI Extention information into $config->extension.
 */
function loadSiExtentions() {
    global $config;
    global $dbh;
    global $databaseBuilt;
    global $patchCount;
    global $ext_names;

    $domain_id = domain_id::get();

    if ($databaseBuilt) {
        if ($patchCount > "196") {
            // @formatter:off
            $sql = "SELECT * from " . TB_PREFIX . "extensions
                    WHERE (domain_id = :domain_id OR
                           domain_id =  0 )
                    ORDER BY domain_id ASC";
            // @formatter:on
            $sth = dbQuery($sql, ':domain_id', $domain_id) or die(htmlsafe(end($dbh->errorInfo())));

            while ($this_extension = $sth->fetch()) {
                $DB_extensions[$this_extension['name']] = $this_extension;
            }
            $config->extension = $DB_extensions;
        }
    }

    // If no extension loaded, load Core
    if (!$config->extension) {
        // @formatter:off
        $extension_core = new Zend_Config(
                        array('core' => array('id'   => 1,
                                        'domain_id'  => 1,
                                        'name'       => 'core',
                                        'description'=> 'Core part of SimpleInvoices - always enabled',
                                        'enabled'    => 1)));
        $config->extension = $extension_core;
        // @formatter:on
    }

    // Populate the array of enabled extensions.
    foreach ($config->extension as $extension) {
        if ($extension->enabled == "1") {
            $ext_names[] = $extension->name;
        }
    }
}

/**
 * Manual verification of foreign keys.
 * Performs some manual FK checks on tables that the invoice table refers to.
 * Under normal conditions, this function will return true. Returning false
 * indicates that if the INSERT or UPDATE were to proceed, bad data could be
 * written to the database.
 * @param int $biller Unique ID for si_biller table.
 * @param int $customer Unique ID for si_customers table.
 * @param int $type Unique ID for si_invoice_type table.
 * @param int $preference Unique ID for si_preferences table.
 * @return boolean true if keys all test true; false otherwise.
 */
function _invoice_check_fk($biller, $customer, $type, $preference) {
    global $dbh;
    $domain_id = domain_id::get();

    // Check biller
    $sth = $dbh->prepare('SELECT count(id) FROM ' . TB_PREFIX . 'biller
                          WHERE id = :id AND domain_id = :domain_id');
    $sth->execute(array(':id' => $biller, ':domain_id' => $domain_id));
    if ($sth->fetchColumn() == 0) {
        return false;
    }
    // Check customer
    $sth = $dbh->prepare('SELECT count(id) FROM ' . TB_PREFIX . 'customers
                          WHERE id = :id AND domain_id = :domain_id');
    $sth->execute(array(':id' => $customer, ':domain_id' => $domain_id));
    if ($sth->fetchColumn() == 0) {
        return false;
    }
    // Check invoice type
    $sth = $dbh->prepare('SELECT count(inv_ty_id) FROM ' . TB_PREFIX . 'invoice_type
                          WHERE inv_ty_id = :id');
    $sth->execute(array(':id' => $type));
    if ($sth->fetchColumn() == 0) {
        return false;
    }
    // Check preferences
    $sth = $dbh->prepare('SELECT count(pref_id) FROM ' . TB_PREFIX . 'preferences
                          WHERE pref_id = :id AND domain_id = :domain_id');
    $sth->execute(array(':id' => $preference, ':domain_id' => $domain_id));
    if ($sth->fetchColumn() == 0) {
        return false;
    }

    // All good
    return true;
}

/**
 * Manual verification of foreign keys.
 * Performs some manual FK checks on tables that the invoice table refers to.
 * Under normal conditions, this function will return true. Returning false
 * indicates that if the INSERT or UPDATE were to proceed, bad data could be
 * written to the database.
 * @param int $invoice Unique ID for si_biller table.
 * @param int $product Unique ID for si_customers table.
 * @param int $tax Unique ID for si_invoice_type table.
 * @param int $update Unique ID for si_preferences table.
 * @return boolean true if keys all test true; false otherwise.
 */
function _invoice_items_check_fk($invoice, $product, $tax, $update) {
    global $dbh;
    $domain_id = domain_id::get();

    // Check invoice
    if (is_null($update) || !is_null($invoice)) {
        $sth = $dbh->prepare('SELECT count(id) FROM ' . TB_PREFIX . 'invoices
                              WHERE id = :id AND domain_id = :domain_id');
        $sth->execute(array(':id' => $invoice, ':domain_id' => $domain_id));
        if ($sth->fetchColumn() == 0) {
            return false;
        }
    }
    // Check product
    $sth = $dbh->prepare('SELECT count(id) FROM ' . TB_PREFIX . 'products
                          WHERE id = :id AND domain_id = :domain_id');
    $sth->execute(array(':id' => $product, ':domain_id' => $domain_id));
    if ($sth->fetchColumn() == 0) {
        return false;
    }
    // Check tax id
    $sth = $dbh->prepare('SELECT count(tax_id) FROM ' . TB_PREFIX . 'tax
                          WHERE tax_id = :id AND domain_id = :domain_id');
    $sth->execute(array(':id' => $tax, ':domain_id' => $domain_id));
    if ($sth->fetchColumn() == 0) {
        return false;
    }

    // All good
    return true;
}

/**
 * Generic record selection function.
 * @param string $table Table to access.
 * @param int $id Unique ID of record to retrieve.
 * @param int $domain_id Domain ID logged into.
 * @param string $id_field Name of ID field.
 * @return array Row retrieved. Test for "=== false" to check for failure.
 */
function getGenericRecord($table, $id, $domain_id = '', $id_field = 'id') {
    $domain_id = domain_id::get($domain_id);

    $record_sql = "SELECT * FROM `" . TB_PREFIX . $table ."`
                   WHERE `$id_field` = :id and `domain_id` = :domain_id";
    $sth = dbQuery($record_sql, ':id', $id, ':domain_id', $domain_id);
    return $sth->fetch();
}

require_once './include/class/WhereClause.php';
/**
 * Dynamically builds and executes a PDO request for a specified table.
 * @param string $request Type of request. Valid settings are: <b>INSERT</b>, <b>UPDATE</b>, <b>DELETE</b>.
 * @param string $table Database table name. Note that <b>TB_PREFIX</b> will be pre-pended
 *        to this value if not already present.
 * @param array $excluded_fields Array of column names to not use from the <b>$_POST</b> array. These
 *        fields might be present in the WHERE clause but are to be excluded from the INSERT or UPDATE
 *        fields. Typically this is the unique identifier for the record.
 * @param WhereClause $whereClause Object with information needed to build the
 *        <b>WHERE</b> clause for the requested statement.
 * @throws Exception if any unexpected setting or missing information is encountered.
 */
function pdoRequest($request, $table, $excluded_fields, $whereClause = NULL) {
    global $dbh;
    global $pdoAdapter;

    if (!($columns = getTableFields($table))) {
        throw new Exception("pdoRequest - Invalid table, $table, specified.");
    }

    $wherePresent = !empty($whereClause);
    if ($wherePresent && !($whereClause instanceof WhereClause)) {
        throw new Exception("pdoRequest - Non-empty \$whereClause parameter is not an " .
                            "instance of the WhereClause class.");
    }

    $value_pairs = array();
    $where = ($wherePresent ? $whereClause->build($parm_list) : '');
    // @formatter:off

    $sql = "";
    switch ($pdoAdapter) {
        case "pgsql":
            break;

        case "mysql":
            foreach ($columns as $column) {
                if (isset($_POST[$column])) {
                    $value_pairs[$column] = $_POST[$column];
                }
            }

            $parm_list = array();
            switch (strtoupper($request)) {
                case "INSERT":
                    $sql = "INSERT INTO " . TB_PREFIX . $table . " (\n";
                    $col_list = "";
                    $val_list = "";
                    $first = true;
                    foreach ($value_pairs as $column => $value) {
                        $token = ':' . $column;
                        // Don't add excluded fields to the fields being updated but
                        // do add them to the parameter list incase they are needed
                        // the WHERE clause.
                        if (!isset($excluded_fields[$column])) {
                            $col_list .= ($first ? "" : ",\n") . $column;
                            $val_list .= ($first ? "" : ",\n") . $token;
                            $first     = false;
                        }
                        $parm_list[$token] = $value;
                    }
                    $sql .= $col_list . ")\n VALUES (\n" . $val_list . ")";
                    break;

                case "UPDATE":
                    $sql = "UPDATE " . TB_PREFIX . $table . " SET \n";
                    $col_token_list = "";
                    $first = true;
                    foreach ($value_pairs as $column => $value) {
                        $token = ':' . $column;
                        // Don't add excluded fields to the fields being updated but
                        // do add them to the parameter list incase they are needed
                        // the WHERE clause.
                        if (!isset($excluded_fields[$column])) {
                            $col_token_list .= ($first ? "" : ",\n") . $column . " = " . $token;
                            $first = false;
                        }
                        $parm_list[$token] = $value;
                    }
                    $sql .= $col_token_list . ' ' . $where;
                    break;

                case "DELETE":
                    break;

                default:
                    error_log("pdoRequest - Request, $request, not implemented.");
                    throw new Exception("pdoRequest - Request, $request, not implemented.");
            }

            if (!($sth = $dbh->prepare($sql))) {
                error_log("pdoRequest - prepare() error." . print_r($sth->errorInfo(),true));
                throw new Exception('pdoRequest - prepare() error. See error_log.');
            }

            foreach ($parm_list as $key => $val) {
                if (!$sth->bindValue($key, $val)) {
                    error_log("pdoRequest - bindParm error." . print_r($sth->errorInfo(),true));
                    throw new Exception('pdoRequest - bindParam() error. See error_log.');
                }
            }

            if (!$sth->execute()) {
                error_log("pdoRequest - execute() error." . print_r($sth->errorInfo(), true));
                throw new Exception('pdoRequest - execute() error. See error_log.');
            }

            dbLogger(interpolateQuery($sql, $parm_list));
            break;

        default:
            error_log("pdoRequest - Server type, $db_server, not supported.");
            throw new Exception("pdoRequest - Server type, $db_server, not supported.");
    }
    // @formatter:on
}

/**
 * Get a customer record.
 * @param string $id Unique ID record to retrieve.
 * @param string $domain_id Domain ID logged into.
 * @return array Row retrieved. Test for "=== false" to check for failure.
 */
function getCustomer($id, $domain_id = '') {
    return getGenericRecord('customers', $id, $domain_id);
}

/**
 * Get a biller record.
 * @param string $id Unique ID record to retrieve.
 * @param string $domain_id Domain ID logged into.
 * @return array Row retrieved. Test for "=== false" to check for failure.
 */
function getBiller($id, $domain_id = '') {
    global $LANG;
    $record = getGenericRecord('biller', $id, $domain_id);
    $record['wording_for_enabled'] = $record['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled'];
    return $record;
}

/**
 * Get a preference record.
 * @param string $id Unique ID record to retrieve.
 * @param string $domain_id Domain ID logged into.
 * @return array Row retrieved. Test for "=== false" to check for failure.
 */
function getPreference($id, $domain_id = '') {
    global $LANG;
    $record = getGenericRecord('preferences', $id, $domain_id, 'pref_id');
    $record['status_wording'] = $record['status'] == 1 ? $LANG['real'] : $LANG['draft'];
    $record['enabled'] = $record['pref_enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled'];
    return $record;
}

/**
 * Get a tax record.
 * @param string $id Unique ID record to retrieve.
 * @param string $domain_id Domain ID logged into.
 * @return array Row retrieved. Test for "=== false" to check for failure.
 */
function getTaxRate($id, $domain_id = '') {
    global $LANG;
    $record = getGenericRecord('tax', $id, $domain_id, 'tax_id');
    $record['enabled'] = $record['tax_enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled'];
    return $record;
}

/**
 * Get all patches.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getSQLPatches() {
    $sql = "SELECT * FROM " . TB_PREFIX . "sql_patchmanager
            WHERE NOT (sql_patch = '' AND sql_release='' AND sql_statement = '')
            ORDER BY sql_release, sql_patch_ref";
    $sth = dbQuery($sql);
    return $sth->fetchAll();
}

/**
 * Get all preferences records.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 *         Note that a field named, "enabled", was added to store the $LANG
 *         enable or disabled word depending on the "pref_enabled" setting
 *         of the record.
 */
function getPreferences($domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT * FROM " . TB_PREFIX . "preferences
            WHERE domain_id = :domain_id ORDER BY pref_description";
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $preferences = NULL;

    for ($i = 0; $preference = $sth->fetch(); $i++) {
        $preference['enabled'] = ($preference['pref_enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
        $preferences[$i] = $preference;
    }

    return $preferences;
}

/**
 * Get all active taxes records.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 *         Note that a field named, "enabled", was added to store the $LANG
 *         enable word.
 */
function getActiveTaxes($domain_id = '') {
    global $LANG;
    global $db_server;
    $domain_id = domain_id::get($domain_id);

    // @formatter:off
    if ($db_server == 'pgsql') {
        $sql = "SELECT * FROM " . TB_PREFIX . "tax
                WHERE tax_enabled
                ORDER BY tax_description";
    } else {
        $sql = "SELECT * FROM " . TB_PREFIX . "tax
                WHERE tax_enabled != 0 and domain_id = :domain_id
                ORDER BY tax_description";
    }
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $taxes = NULL;

    for ($i = 0; $tax = $sth->fetch(); $i++) {
        $tax['enabled'] = $LANG['enabled'];
        $taxes[$i] = $tax;
    }

    return $taxes;
}

/**
 * Get active preferences records.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getActivePreferences($domain_id = '') {
    $domain_id = domain_id::get($domain_id);

    // @formatter:off
    $sql = "SELECT * FROM " . TB_PREFIX . "preferences
            WHERE pref_enabled and domain_id = :domain_id
            ORDER BY pref_description";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    return $sth->fetchAll();
}

/**
 * Get custom field labels.
 * @param string $domain_id Domain ID logged info.
 * @param boolean $noUndefinedLabels Defaults to <b>false</b>. When set to
 *        <b>true</b> custom fields that do not have a label defined will
 *        not a be assigned a default label so the undefined custom fields
 *        won't be displayed.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getCustomFieldLabels($domain_id = '', $noUndefinedLabels = FALSE) {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT * FROM " . TB_PREFIX . "custom_fields
            WHERE domain_id = :domain_id ORDER BY cf_custom_field";
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $cfl = $LANG['custom_field'] . ' ';
    for ($i = 0; $customField = $sth->fetch(); $i++) {
        // @formatter:off
        $customFields[$customField['cf_custom_field']] =
                (empty($customField['cf_custom_label']) ? ($noUndefinedLabels ? "" : $cfl . (($i % 4) + 1)) :
                                                          $customField['cf_custom_label']);
        // @formatter:on
    }
    return $customFields;
}

/**
 * Get biller records.
 * @param string $domain_id Domain ID logged info.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 *         Note that a field named, "enabled", was added to store the $LANG
 *         enable or disabled word depending on the "pref_enabled" setting
 *         of the record.
 */
function getBillers($domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT * FROM " . TB_PREFIX . "biller WHERE domain_id = :domain_id ORDER BY name";
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $billers = NULL;

    for ($i = 0; $biller = $sth->fetch(); $i++) {
        $biller['enabled'] = ($biller['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
        $billers[$i] = $biller;
    }

    return $billers;
}

/**
 * Get active biller records.
 * @param string $domain_id Domain ID logged info.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getActiveBillers($domain_id = '') {
    global $db_server;
    $domain_id = domain_id::get($domain_id);

    if ($db_server == 'pgsql') {
        $sql = "SELECT * FROM " . TB_PREFIX . "biller
                WHERE enabled AND domain_id = :domain_id ORDER BY name";
    } else {
        $sql = "SELECT * FROM " . TB_PREFIX . "biller
                WHERE enabled != 0 AND domain_id = :domain_id ORDER BY name";
    }
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    return $sth->fetchAll();
}

/**
 * Get tax types
 * @return string[] Types of tax records (% - percentage, $ - dollars)
 */
function getTaxTypes() {
    $types = array('%' => '%', '$' => '$');
    return $types;
}

/**
 * Get a specific payment type record.
 * @param int $id Unique ID of record to retrieve.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 *         Note that a field named, "enabled", was added to store the $LANG
 *         enable or disabled word depending on the "pref_enabled" setting
 *         of the record.
 */
function getPaymentType($id, $domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT * FROM " . TB_PREFIX . "payment_types
            WHERE pt_id = :id AND domain_id = :domain_id";
    $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
    $paymentType = $sth->fetch();
    $paymentType['enabled'] = ($paymentType['pt_enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);

    return $paymentType;
}

/**
 * Get a specific payment record.
 * @param int $id Unique ID of record to retrieve.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getPayment($id, $domain_id = '') {
    global $config;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT
                ap.*,
                c.id   AS customer_id,
                c.name AS customer,
                b.id   AS biller_id,
                b.name AS biller
            FROM " . TB_PREFIX . "payment ap,
                 " . TB_PREFIX . "invoices iv,
                 " . TB_PREFIX . "customers c,
                 " . TB_PREFIX . "biller b
            WHERE ap.ac_inv_id   = iv.id
              AND iv.customer_id = c.id
              AND iv.biller_id   = b.id
              AND ap.id          = :id
              AND ap.domain_id   = :domain_id";
    // @formatter:on
    $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
    $payment = $sth->fetch();
    $payment['date'] = siLocal::date($payment['ac_date']);
    return $payment;
}

/**
 * Get a specific payment type record.
 * @param int $id Unique ID of invoice to retrieve payments for.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getInvoicePayments($id, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT
                ap.*,
                c.name AS cname,
                b.name AS bname
            FROM
                " . TB_PREFIX . "payment ap,
                " . TB_PREFIX . "invoices iv,
                " . TB_PREFIX . "customers c,
                " . TB_PREFIX . "biller b
            WHERE ap.ac_inv_id   = :id
              AND ap.domain_id   = :domain_id
              AND ap.ac_inv_id   = iv.id
              AND iv.domain_id   = ap.domain_id
              AND iv.customer_id = c.id
              AND c.domain_id    = iv.domain_id
              AND iv.biller_id   = b.id
              AND b.domain_id    = iv.domain_id
            ORDER BY
                ap.id DESC";
    // @formatter:on
    return dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
}

/**
 * Get a specific payment type record.
 * @param int $id Unique ID of customer to retrieve payments for.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getCustomerPayments($id, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT
                ap.*,
                c.name AS cname,
                b.name AS bname
            FROM
                " . TB_PREFIX . "payment ap,
                " . TB_PREFIX . "invoices iv,
                " . TB_PREFIX . "customers c,
                " . TB_PREFIX . "biller b
            WHERE c.id = :id
              AND ap.domain_id   = :domain_id
              AND ap.ac_inv_id   = iv.id
              AND iv.domain_id   = ap.domain_id
              AND iv.customer_id = c.id
              AND c.domain_id    = iv.domain_id
              AND iv.biller_id   = b.id
              AND b.domain_id    = iv.domain_id
            ORDER BY
                ap.id DESC";
    // @formatter:on
    return dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
}

/**
 * Get a all payment records.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getPayments($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT
                ap.*,
                c.name AS cname,
                b.name AS bname
            FROM
                " . TB_PREFIX . "payment ap,
                " . TB_PREFIX . "invoices iv,
                " . TB_PREFIX . "customers c,
                " . TB_PREFIX . "biller b
            WHERE ap.domain_id   = :domain_id
              AND ap.ac_inv_id   = iv.id
              AND iv.domain_id   = ap.domain_id
              AND iv.customer_id = c.id
              AND c.domain_id    = iv.domain_id
              AND iv.biller_id   = b.id
              AND b.domain_id    = iv.domain_id
            ORDER BY
                ap.id DESC";
    // @formatter:on
    return dbQuery($sql, ':domain_id', $domain_id);
}

/**
 * Add payment description to retrieved payment records.
 * @param mixed $sth Result of dbQuery() on payment records.
 * @param string $domain_id Domain ID logged into.
 * @return array Payment records with "description" added.
 */
function progressPayments($sth, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    $payments = NULL;

    for ($i = 0; $payment = $sth->fetch(); $i++) {
        $sql = "SELECT pt_description FROM " . TB_PREFIX . "payment_types
                WHERE pt_id = :id and domain_id = :domain_id";
        $tth = dbQuery($sql, ':id', $payment['ac_payment_type'], ':domain_id', $domain_id);

        $pt = $tth->fetch();

        $payments[$i] = $payment;
        $payments[$i]['description'] = $pt['pt_description'];
    }

    return $payments;
}

/**
 * Get payment type records.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 *         Note that a field named, "enabled", was added to store the $LANG
 *         enable or disabled word depending on the "pref_enabled" setting
 *         of the record.
 */
function getPaymentTypes($domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT * FROM " . TB_PREFIX . "payment_types
            WHERE domain_id = :domain_id ORDER BY pt_description";
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $paymentTypes = NULL;

    for ($i = 0; $paymentType = $sth->fetch(); $i++) {
        $paymentType['pt_enabled'] = ($paymentType['pt_enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
        $paymentTypes[$i] = $paymentType;
    }

    return $paymentTypes;
}

/**
 * Get payment type records.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 *         Note that a field named, "enabled", was added to store the $LANG
 *         enable or disabled word depending on the "pref_enabled" setting
 *         of the record.
 */
function getActivePaymentTypes($domain_id = '') {
    global $LANG;
    global $db_server;
    $domain_id = domain_id::get($domain_id);

    // @formatter:off
    if ($db_server == 'pgsql') {
        $sql = "SELECT * FROM " . TB_PREFIX . "payment_types
                WHERE pt_enabled and domain_id = :domain_id
                ORDER BY pt_description";
    } else {
        $sql = "SELECT * FROM " . TB_PREFIX . "payment_types
                WHERE pt_enabled != 0 and domain_id = :domain_id
                ORDER BY pt_description";
    }
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $paymentTypes = array();
    for ($i = 0; $paymentType = $sth->fetch(); $i++) {
        $paymentType['enabled'] = ($paymentType['pt_enabled'] != 0 ? $LANG['enabled'] : $LANG['disabled']);
        $paymentTypes[$i] = $paymentType;
    }

    return $paymentTypes;
}

/**
 * Get a specific product record.
 * @param int $id Unique ID of record to retrieve.
 * @param string $domain_id Domain ID logged into.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 *         Note that a field named, "wording_for_enabled", was added to store the $LANG
 *         enable or disabled word depending on the "pref_enabled" setting
 *         of the record.
 */
function getProduct($id, $domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT * FROM " . TB_PREFIX . "products
            WHERE id = :id and domain_id = :domain_id";
    $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
    $product = $sth->fetch();
    $product['wording_for_enabled'] = ($product['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
    return $product;
}

/**
 * Insert a new record in the products table.
 * @param number $enabled Product enabled/disabled status.
 *        Set to 1 (default) for enabled; 0 for disabled.
 * @param number $visible Flags record seen in list. Defaults to 1 (visible).
 *        Set to 0 for not visible.
 * @param string $domain_id Domain user is logged into.
 * @return PDO statement object on success, false on failure.
 */
function insertProduct($enabled = 1, $visible = 1, $domain_id = '') {
    if (function_exists(insertProduct_cflgs)) {
        return insertProduct_cflgs($enabled, $visible, $domain_id);
    }

    $domain_id = domain_id::get($domain_id);

    if (isset($_POST['enabled'])) $enabled = $_POST['enabled'];
    // select all attribts
    $sql = "SELECT * FROM " . TB_PREFIX . "products_attributes";
    $sth = dbQuery($sql);
    $attributes = $sth->fetchAll();

    $attr = array();
    foreach ($attributes as $k => $v) {
        if ($_POST['attribute' . $v[id]] == 'true') {
            $attr[$v['id']] = $_POST['attribute' . $v[id]];
        }
    }

    $notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
    $show_description = ($_POST['show_description'] == 'true' ? 'Y' : NULL);

    // @formatter:off
    $sql = "INSERT into " . TB_PREFIX . "products (
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
            VALUES (
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
    return dbQuery($sql, ':domain_id'           , $domain_id,
                         ':description'         , $_POST['description'],
                         ':unit_price'          , $_POST['unit_price'],
                         ':cost'                , $_POST['cost'],
                         ':reorder_level'       , $_POST['reorder_level'],
                         ':custom_field1'       , $_POST['custom_field1'],
                         ':custom_field2'       , $_POST['custom_field2'],
                         ':custom_field3'       , $_POST['custom_field3'],
                         ':custom_field4'       , $_POST['custom_field4'],
                         ':notes'               , $_POST['notes'],
                         ':default_tax_id'      , $_POST['default_tax_id'],
                         ':enabled'             , $enabled,
                         ':visible'             , $visible,
                         ':attribute'           , json_encode($attr),
                         ':notes_as_description', $notes_as_description,
                         ':show_description'    , $show_description);
    // @formatter:off
}

/**
 * Update a product record.
 * @param string $domain_id Domain user is logged into.
 * @return PDO statement object on success, false on failure.
 */
function updateProduct($domain_id = '') {
    if (function_exists(updateProduct_cflgs)) {
        return updateProduct_cflgs($domain_id);
    }
    $domain_id = domain_id::get($domain_id);

    // select all attributes
    $sql = "SELECT * FROM " . TB_PREFIX . "products_attributes";
    $sth = dbQuery($sql);
    $attributes = $sth->fetchAll();

    $attr = array();
    foreach ($attributes as $k => $v) {
        if ($_POST['attribute' . $v[id]] == 'true') {
            $attr[$v['id']] = $_POST['attribute' . $v[id]];
        }
    }
    $notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
    $show_description = ($_POST['show_description'] == 'true' ? 'Y' : NULL);

    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "products
            SET description          = :description,
                enabled              = :enabled,
                default_tax_id       = :default_tax_id,
                notes                = :notes,
                custom_field1        = :custom_field1,
                custom_field2        = :custom_field2,
                custom_field3        = :custom_field3,
                custom_field4        = :custom_field4,
                unit_price           = :unit_price,
                cost                 = :cost,
                reorder_level        = :reorder_level,
                attribute            = :attribute,
                notes_as_description = :notes_as_description,
                show_description     = :show_description
            WHERE id        = :id
              AND domain_id = :domain_id";
    return dbQuery($sql, ':domain_id'           , $domain_id,
                         ':description'         , $_POST[description],
                         ':enabled'             , $_POST['enabled'],
                         ':notes'               , $_POST[notes],
                         ':default_tax_id'      , $_POST['default_tax_id'],
                         ':custom_field1'       , $_POST[custom_field1],
                         ':custom_field2'       , $_POST[custom_field2],
                         ':custom_field3'       , $_POST[custom_field3],
                         ':custom_field4'       , $_POST[custom_field4],
                         ':unit_price'          , $_POST[unit_price],
                         ':cost'                , $_POST[cost],
                         ':reorder_level'       , $_POST[reorder_level],
                         ':attribute'           , json_encode($attr),
                         ':notes_as_description', $notes_as_description,
                         ':show_description'    , $show_description,
                         ':id'                  , $_GET[id]);
    // @formatter:on
}

/**
 * Get all product records that are flagged visible.
 * @param string $domain_id Domain user is logged into.
 * @return PDO statement object on success, false on failure.
 */
function getProducts($domain_id = '') {
    global $LANG;
    global $db_server;
    $domain_id = domain_id::get($domain_id);

    // @formatter:off
    if ($db_server == 'pgsql') {
        $sql = "SELECT * FROM " . TB_PREFIX . "products
                WHERE visible and domain_id = :domain_id ORDER BY description";
    } else {
        $sql = "SELECT * FROM " . TB_PREFIX . "products
                WHERE visible = 1 AND domain_id = :domain_id ORDER BY description";
    }
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $products = NULL;

    for ($i = 0; $product = $sth->fetch(); $i++) {
        if ($product['enabled'] == 1) {
            $product['enabled'] = $LANG['enabled'];
        } else {
            $product['enabled'] = $LANG['disabled'];
        }

        $products[$i] = $product;
    }

    return $products;
}

/**
 * Get active (enabled) product rows.
 * @param string $domain_id Domain user is logged into.
 * @return array Rows retrieved.
 */
function getActiveProducts($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT * FROM " . TB_PREFIX . "products
            WHERE enabled AND domain_id = :domain_id ORDER BY description";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    return $sth->fetchAll();
}

/**
 * Get tax table rows.
 * @param string $domain_id Domain user is logged into.
 * @return array Rows retrieved.
 *         Note that a field named, "wording_for_enabled", was added to store the $LANG
 *         enable or disabled word depending on the "pref_enabled" setting
 *         of the record.
 */
function getTaxes($domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT * FROM " . TB_PREFIX . "tax
            WHERE domain_id = :domain_id ORDER BY tax_description";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $taxes = NULL;

    for ($i = 0; $tax = $sth->fetch(); $i++) {
        $tax['enabled'] = ($tax['tax_enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
        $taxes[$i] = $tax;
    }

    return $taxes;
}

/**
 * Get a specific si_system_defaults record.
 * @param string $name Name for record to retrieve.
 * @param string $bool If true (default), a boolean field is being retrieved.
 *        If false, a character field is being retrieved.
 *        Note: If true, the result will be the $LANG word for 'enabled' or 'disabled'.
 * @param string $domain_id Domain user is logged into.
 * @return mixed Value from database or for bool, 'enabled' or 'disabled' word.
 */
function getDefaultGeneric($name, $bool = true, $domain_id = '') {
    global $LANG;
    global $databaseBuilt;

    // Make the value to return on a false or no DB build condition.
    $failed = ($bool ? $LANG['disabled'] : 0);
    if (!$databaseBuilt) return $failed;

    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT value FROM " . TB_PREFIX . "system_defaults s
            WHERE ( s.name = :param AND s.domain_id = :domain_id)";
    // @formatter:on
    $sth = dbQuery($sql, ':param', $name, ':domain_id', $domain_id);
    if ($sth === false) return $failed;

    $array = $sth->fetch();
    $nameval = ($bool ? ($array['value'] == 1 ? $LANG['enabled'] : $LANG['disabled']) : $array['value']);
    return $nameval;
}

/**
 * Get a default customer name.
 * @param string $domain_id Domain user is logged into.
 * @return string Default customer name
 */
function getDefaultCustomer($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT c.name AS name FROM " .
                TB_PREFIX . "customers c, " .
                TB_PREFIX . "system_defaults s
            WHERE ( s.name      = 'customer'
                AND c.id        = s.value
                AND c.domain_id = s.domain_id
                AND s.domain_id = :domain_id)";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    return $sth->fetch();
}

/**
 * Get a default payment type.
 * @param string $domain_id Domain user is logged into.
 * @return string Default payment type.
 */
function getDefaultPaymentType($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT p.pt_description AS pt_description
            FROM " . TB_PREFIX . "payment_types p, " .
                     TB_PREFIX . "system_defaults s
            WHERE ( s.name      = 'payment_type'
                AND p.pt_id     = s.value
                AND p.domain_id = s.domain_id
                AND s.domain_id = :domain_id)";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    return $sth->fetch();
}

/**
 * Get a default preference information.
 * @param string $domain_id Domain user is logged into.
 * @return array Preference row and system default setting for it.
 */
function getDefaultPreference($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT * FROM " .
                TB_PREFIX . "preferences p, " .
                TB_PREFIX . "system_defaults s
            WHERE ( s.name      = 'preference'
                AND p.pref_id   = s.value
                AND p.domain_id = s.domain_id
                AND s.domain_id = :domain_id)";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    return $sth->fetch();
}

/**
 * Get a default biller name.
 * @param string $domain_id Domain user is logged into.
 * @return string Default biller name
 */
function getDefaultBiller($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT b.name AS name FROM " .
                TB_PREFIX . "biller b, " .
                TB_PREFIX . "system_defaults s
            WHERE ( s.name      = 'biller'
                AND b.id        = s.value
                AND b.domain_id = s.domain_id
                AND s.domain_id = :domain_id)";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    return $sth->fetch();
}

/**
 * Get a default tax record.
 * @param string $domain_id Domain user is logged into.
 * @return array Default tax record.
 */
function getDefaultTax($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT * FROM " .
                TB_PREFIX . "tax t, " .
                TB_PREFIX . "system_defaults s
            WHERE ( s.name      = 'tax'
                AND t.tax_id    = s.value
                AND t.domain_id = s.domain_id
                AND s.domain_id = :domain_id";
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    return $sth->fetch();
}

/**
 * Get "delete" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultDelete() {
    return getDefaultGeneric('delete');
}

/**
 * Get "logging" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultLogging() {
    return getDefaultGeneric('logging');
}

/**
 * Get "loggin" entry from the system_defaults table.
 * @return string "1" or "0"
 */
function getDefaultLoggingStatus() {
    return (getDefaultGeneric('logging', false) == 1);
}

/**
 * Get "inventory" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultInventory() {
    return getDefaultGeneric('inventory');
}

/**
 * Get "product_attributes" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultProductAttributes() {
    return getDefaultGeneric('product_attributes');
}

/**
 * Get "large_dataset" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultLargeDataset() {
    return getDefaultGeneric('large_dataset');
}

/**
 * Get "language" entry from the system_defaults table.
 * @return string Language setting (ex: en_US)
 */
function getDefaultLanguage() {
    return getDefaultGeneric('language', false);
}

function getInvoiceTotal($invoice_id, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT SUM(total) AS total FROM " . TB_PREFIX . "invoice_items
            WHERE invoice_id =  :invoice_id AND domain_id = :domain_id";
    // @formatter:on
    $sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $domain_id);
    $res = $sth->fetch();

    return $res['total'];
}

function setInvoiceStatus($invoice, $status, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "invoices
            SET status_id =  :status
            WHERE id = :id AND domain_id = :domain_id";
    // @formatter:on
    $sth = dbQuery($sql, ':status', $status, ':id', $invoice, ':domain_id', $domain_id);
}

function getInvoice($id, $domain_id = '') {
    global $config;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT * FROM " . TB_PREFIX . "invoices
            WHERE id =  :id AND domain_id =  :domain_id";
    // @formatter:on
    $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);

    $invoice = $sth->fetch();

    $invoice['calc_date'] = date('Y-m-d', strtotime($invoice['date']));
    $invoice['date'] = siLocal::date($invoice['date']);
    $invoice['total'] = getInvoiceTotal($invoice['id']);

    $invoiceobj = new invoice();
    $invoiceobj->domain_id = $domain_id;

    $invoice['gross'] = $invoiceobj->getInvoiceGross($invoice['id']);
    $invoice['paid'] = calc_invoice_paid($invoice['id']);
    $invoice['owing'] = $invoice['total'] - $invoice['paid'];

    // invoice total tax
    // @formatter:off
    $sql = "SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total
            FROM " . TB_PREFIX . "invoice_items
            WHERE invoice_id =  :id AND domain_id =  :domain_id";
    // @formatter:on
    $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
    $result = $sth->fetch();

    $invoice['total_tax'] = $result['total_tax'];
    $invoice['tax_grouped'] = taxesGroupedForInvoice($id);

    return $invoice;
}

/*
 * Function: taxesGroupedForInvoice
 * Purpose: to show a nice summary of total $ for tax for an invoice
 */
function numberOfTaxesForInvoice($invoice_id, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT DISTINCT tax.tax_id
            FROM " . TB_PREFIX . "invoice_item_tax item_tax,
                 " . TB_PREFIX . "invoice_items item,
                 " . TB_PREFIX . "tax tax
            WHERE item.id         = item_tax.invoice_item_id
              AND tax.tax_id      = item_tax.tax_id
              AND tax.domain_id   = item.domain_id
              AND item.invoice_id = :invoice_id
              AND tax.domain_id   = :domain_id
            GROUP BY tax.tax_id;";
    // @formatter:on
    $sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $domain_id);
    $result = $sth->rowCount();

    return $result;
}

/*
 * Function: taxesGroupedForInvoice
 * Purpose: to show a nice summary of total $ for tax for an invoice
 */
function taxesGroupedForInvoice($invoice_id, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT tax.tax_description      as tax_name,
                   SUM(item_tax.tax_amount) as tax_amount,
                   item_tax.tax_rate        as tax_rate,
                   count(*)                 as count
            FROM " . TB_PREFIX . "invoice_item_tax item_tax,
                 " . TB_PREFIX . "invoice_items item,
                 " . TB_PREFIX . "tax tax
            WHERE item.id         = item_tax.invoice_item_id
              AND tax.tax_id      = item_tax.tax_id
              AND tax.domain_id   = item.domain_id
              AND item.invoice_id = :invoice_id
              AND tax.domain_id   = :domain_id
            GROUP BY tax.tax_id;";
    // @formatter:on
    $sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $domain_id);
    $result = $sth->fetchAll();

    return $result;
}

/*
 * Function: taxesGroupedForInvoiceItem
 * Purpose: to show a nice summary of total $ for tax for an invoice item - used for invoice editing
 */
function taxesGroupedForInvoiceItem($invoice_item_id, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT item_tax.id as row_id,
                   tax.tax_description as tax_name,
                   tax.tax_id as tax_id
            FROM " . TB_PREFIX . "invoice_item_tax item_tax,
                 " . TB_PREFIX . "tax tax
            WHERE item_tax.invoice_item_id = :invoice_item_id
              AND tax.tax_id               = item_tax.tax_id
              AND tax.domain_id            = :domain_id
            ORDER BY row_id ASC;";
    // @formatter:on
    $sth = dbQuery($sql, ':invoice_item_id', $invoice_item_id, ':domain_id', $domain_id);
    $result = $sth->fetchAll();

    return $result;
}

function setStatusExtension($extension_id, $status = 2, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);

    // status=2 = toggle status
    if ($status == 2) {
        // @formatter:off
        $sql = "SELECT enabled FROM " . TB_PREFIX . "extensions
                WHERE id = :id AND domain_id = :domain_id LIMIT 1";
        // @formatter:on
        $sth = dbQuery($sql, ':id', $extension_id, ':domain_id', $domain_id);
        $extension_info = $sth->fetch();
        $status = 1 - $extension_info['enabled'];
    }

    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "extensions
            SET enabled =  :status
            WHERE id = :id AND domain_id =  :domain_id";
    // @formatter:on
    if (dbQuery($sql, ':status', $status, ':id', $extension_id, ':domain_id', $domain_id)) {
        return true;
    }
    return false;
}

function getExtensionID($extension_name = "none", $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT * FROM " . TB_PREFIX . "extensions
            WHERE name = :extension_name
              AND (domain_id = 0 OR domain_id = :domain_id )
            ORDER BY domain_id DESC LIMIT 1";
    // @formatter:on
    $sth = dbQuery($sql, ':extension_name', $extension_name, ':domain_id', $domain_id);
    $extension_info = $sth->fetch();
    if (!$extension_info) {
        return -2; // -2 = no result set = extension not found
    } elseif ($extension_info['enabled'] == 0) {
        return -1; // -1 = extension not enabled
    }
    return $extension_info['id']; // 0 = core, >0 is extension id
}

function getSystemDefaults($domain_id = '') {
    global $patchCount;
    global $databaseBuilt;
    if (!$databaseBuilt) return NULL;

    $domain_id = domain_id::get($domain_id);

    $db = new db();

    // get sql patch level - if less than 198 do sql with no exntesion table
    if ((checkTableExists(TB_PREFIX . "system_defaults") == false)) {
        return NULL;
    }

    if ($patchCount < "198") {
        // @formatter:off
        $sql_default = "SELECT def.name, def.value
                        FROM " . TB_PREFIX . "system_defaults def";
        // @formatter:on
        $sth = $db->query($sql_default);
    } else  {
        // @formatter:off
        $sql_default = "SELECT def.name, def.value
                        FROM " . TB_PREFIX . "system_defaults def
                        INNER JOIN " . TB_PREFIX . "extensions ext ON (def.domain_id = ext.domain_id)
                        WHERE enabled = 1
                          AND ext.name = 'core'
                          AND def.domain_id = :domain_id
                        ORDER BY extension_id ASC";     // order is important for overriding setting
        // @formatter:on
        $sth = $db->query($sql_default, ':domain_id', 0);
    }

    $lcl_defaults = NULL;
    $default = NULL;

    while ($default = $sth->fetch()) {
        $lcl_defaults["$default[name]"] = $default['value'];
    }

    if ($patchCount > "198") {
        // @formatter:off
        $sql = "SELECT def.name,def.value
                FROM " . TB_PREFIX . "system_defaults def
                INNER JOIN " . TB_PREFIX . "extensions ext ON (def.extension_id = ext.id)
                WHERE enabled=1
                  AND def.domain_id = :domain_id
                ORDER BY extension_id ASC";
        // @formatter:on
        $sth = $db->query($sql, 'domain_id', $domain_id);
        $default = NULL;

        while ($default = $sth->fetch()) {
            $lcl_defaults["$default[name]"] = $default['value']; // if setting is redefined, overwrite the previous value
        }
    }

    return $lcl_defaults;
}

function updateDefault($name, $value, $extension_name = "core") {
    $domain_id = domain_id::get();

    $extension_id = getExtensionID($extension_name);
    if (!($extension_id >= 0)) {
        die(htmlsafe("Invalid extension name: " . $extension));
    }

    // @formatter:off
    $sql = "INSERT INTO `" . TB_PREFIX . "system_defaults` (
                    `name`,
                    `value`,
                    domain_id,
                    extension_id)
            VALUES (:name,
                    :value,
                    :domain_id,
                    :extension_id)
            ON DUPLICATE KEY UPDATE `value` =  :value";
    if (dbQuery($sql,
                ':value'       , $value,
                ':domain_id'   , $domain_id,
                ':name'        , $name,
                ':extension_id', $extension_id)) return true;
    // @formatter:on
    return false;
}

function getInvoiceType($id) {
    $sql = "SELECT * FROM " . TB_PREFIX . "invoice_type WHERE inv_ty_id = :id";
    $sth = dbQuery($sql, ':id', $id);
    return $sth->fetch();
}

function insertBiller() {
    $domain_id = domain_id::get();

    // @formatter:off
    if ($db_server == 'pgsql') {
        $sql = "INSERT INTO " . TB_PREFIX . "biller (
                        domain_id,
                        name,
                        street_address,
                        street_address2,
                        city,
                        state,
                        zip_code,
                        country,
                        phone,
                        mobile_phone,
                        fax,
                        email,
                        logo,
                        footer,
                        notes,
                        custom_field1,
                        custom_field2,
                        custom_field3,
                        custom_field4,
                        enabled )
                VALUES (:domain_id,
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
                        :enabled )";
    } else {
        $sql = "INSERT INTO " . TB_PREFIX . "biller (
                        id,
                        domain_id,
                        name,
                        street_address,
                        street_address2,
                        city,
                        state,
                        zip_code,
                        country,
                        phone,
                        mobile_phone,
                        fax,
                        email,
                        logo,
                        footer,
                        paypal_business_name,
                        paypal_notify_url,
                        paypal_return_url,
                        eway_customer_id,
                        paymentsgateway_api_id,
                        notes,
                        custom_field1,
                        custom_field2,
                        custom_field3,
                        custom_field4,
                        enabled )
                VALUES (NULL,
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
                        :paymentsgateway_api_id,
                        :notes,
                        :custom_field1,
                        :custom_field2,
                        :custom_field3,
                        :custom_field4,
                        :enabled )";
    }
    return dbQuery($sql, ':name'                   , $_POST['name'],
                          ':street_address'        , $_POST['street_address'],
                          ':street_address2'       , $_POST['street_address2'],
                          ':city'                  , $_POST['city'],
                          ':state'                 , $_POST['state'],
                          ':zip_code'              , $_POST['zip_code'],
                          ':country'               , $_POST['country'],
                          ':phone'                 , $_POST['phone'],
                          ':mobile_phone'          , $_POST['mobile_phone'],
                          ':fax'                   , $_POST['fax'],
                          ':email'                 , $_POST['email'],
                          ':logo'                  , $_POST['logo'],
                          ':footer'                , $_POST['footer'],
                          ':paypal_business_name'  , $_POST['paypal_business_name'],
                          ':paypal_notify_url'     , $_POST['paypal_notify_url'],
                          ':paypal_return_url'     , $_POST['paypal_return_url'],
                          ':eway_customer_id'      , $_POST['eway_customer_id'],
                          ':paymentsgateway_api_id', $_POST['paymentsgateway_api_id'],
                          ':notes'                 , $_POST['notes'],
                          ':custom_field1'         , $_POST['custom_field1'],
                          ':custom_field2'         , $_POST['custom_field2'],
                          ':custom_field3'         , $_POST['custom_field3'],
                          ':custom_field4'         , $_POST['custom_field4'],
                          ':enabled'               , $_POST['enabled'],
                          ':domain_id'             , $domain_id);
    // @formatter:on
}

function updateBiller() {
    $domain_id = domain_id::get();
    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "biller
            SET domain_id              = :domain_id,
                name                   = :name,
                street_address         = :street_address,
                street_address2        = :street_address2,
                city                   = :city,
                state                  = :state,
                zip_code               = :zip_code,
                country                = :country,
                phone                  = :phone,
                mobile_phone           = :mobile_phone,
                fax                    = :fax,
                email                  = :email,
                logo                   = :logo,
                footer                 = :footer,
                paypal_business_name   = :paypal_business_name,
                paypal_notify_url      = :paypal_notify_url,
                paypal_return_url      = :paypal_return_url,
                eway_customer_id       = :eway_customer_id,
                paymentsgateway_api_id = :paymentsgateway_api_id,
                notes                  = :notes,
                custom_field1          = :custom_field1,
                custom_field2          = :custom_field2,
                custom_field3          = :custom_field3,
                custom_field4          = :custom_field4,
                enabled                = :enabled
            WHERE id = :id";
    return dbQuery($sql, ':domain_id'               , $domain_id,
                         ':name'                    , $_POST['name'],
                         ':street_address'          , $_POST['street_address'],
                         ':street_address2'         , $_POST['street_address2'],
                         ':city'                    , $_POST['city'],
                         ':state'                   , $_POST['state'],
                         ':zip_code'                , $_POST['zip_code'],
                         ':country'                 , $_POST['country'],
                         ':phone'                   , $_POST['phone'],
                         ':mobile_phone'            , $_POST['mobile_phone'],
                         ':fax'                     , $_POST['fax'],
                         ':email'                   , $_POST['email'],
                         ':logo'                    , $_POST['logo'],
                         ':footer'                  , $_POST['footer'],
                         ':paypal_business_name'    , $_POST['paypal_business_name'],
                         ':paypal_notify_url'       , $_POST['paypal_notify_url'],
                         ':paypal_return_url'       , $_POST['paypal_return_url'],
                         ':eway_customer_id'        , $_POST['eway_customer_id'],
                         ':paymentsgateway_api_id'  , $_POST['paymentsgateway_api_id'],
                         ':notes'                   , $_POST['notes'],
                         ':custom_field1'           , $_POST['custom_field1'],
                         ':custom_field2'           , $_POST['custom_field2'],
                         ':custom_field3'           , $_POST['custom_field3'],
                         ':custom_field4'           , $_POST['custom_field4'],
                         ':enabled'                 , $_POST['enabled'],
                         ':id'                      , $_GET[id]);
    // @formatter:on
}

function updateCustomer() {
    global $config;
    $domain_id = domain_id::get();

    $cc = $_POST['credit_card_number_new'];
    if (empty($cc)) {
        $cc1 = '';
        $cc2 = '';
    } else {
        $key = $config->encryption->default->key;
        $cc1 = ', credit_card_number = :credit_card_number,';
        $enc = new encryption();
        $cc2 = ", ':credit_card_number', $enc->encrypt($key, $cc),";
    }

    // The $sql and dbQuery statements are structed in a special way.
    // Note that the last item in the $sql SET list has no comma at the end
    // of field prior to the $cc1 variable. Similarly the dbQuery last
    // field before the $cc2 has no comma and appends the $cc2 variable to it.
    // This allows the credit_card_number field to be added when it is present
    // and not added when it is not present. If new fields are added, make sure
    // this special structure is maintained.
    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "customers
            SET domain_id                = :domain_id,
                name                     = :name,
                attention                = :attention,
                street_address           = :street_address,
                street_address2          = :street_address2,
                city                     = :city,
                state                    = :state,
                zip_code                 = :zip_code,
                country                  = :country,
                phone                    = :phone,
                mobile_phone             = :mobile_phone,
                fax                      = :fax,
                email                    = :email,
                notes                    = :notes,
                custom_field1            = :custom_field1,
                custom_field2            = :custom_field2,
                custom_field3            = :custom_field3,
                custom_field4            = :custom_field4,
                enabled                  = :enabled,
                credit_card_holder_name  = :credit_card_holder_name,
                credit_card_expiry_month = :credit_card_expiry_month,
                credit_card_expiry_year  = :credit_card_expiry_year
                $cc1
            WHERE id = :id";

        return dbQuery($sql,
                        ':domain_id'                , $domain_id,
                        ':name'                     , $_POST['name'],
                        ':attention'                , $_POST['attention'],
                        ':street_address'           , $_POST['street_address'],
                        ':street_address2'          , $_POST['street_address2'],
                        ':city'                     , $_POST['city'],
                        ':state'                    , $_POST['state'],
                        ':zip_code'                 , $_POST['zip_code'],
                        ':country'                  , $_POST['country'],
                        ':phone'                    , $_POST['phone'],
                        ':mobile_phone'             , $_POST['mobile_phone'],
                        ':fax'                      , $_POST['fax'],
                        ':email'                    , $_POST['email'],
                        ':notes'                    , $_POST['notes'],
                        ':custom_field1'            , $_POST['custom_field1'],
                        ':custom_field2'            , $_POST['custom_field2'],
                        ':custom_field3'            , $_POST['custom_field3'],
                        ':custom_field4'            , $_POST['custom_field4'],
                        ':enabled'                  , $_POST['enabled'],
                        ':id'                       , $_GET['id'],
                        ':credit_card_holder_name'  , $_POST['credit_card_holder_name'],
                        ':credit_card_expiry_month' , $_POST['credit_card_expiry_month'],
                        ':credit_card_expiry_year'  , $_POST['credit_card_expiry_year'] .
                        $cc2);
    // @formatter:on
}

function insertCustomer() {
    global $config;
    $domain_id = domain_id::get();

    // Extract all the fields from the $_POST associative array into fields
    // named by the field's key in the associative array. For example:
    // $_POST['field1'] contains 'value1'
    // $_POST['field2'] contains 'value2'
    // So export($_POST) results in the following variables and values:
    // $field1 contains 'value1'
    // $field2 contains 'value2'
    extract($_POST);

    $enc = new encryption();
    $key = $config->encryption->default->key;
    $encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);
    // @formatter:off
    $sql = "INSERT INTO " . TB_PREFIX . "customers (
                    domain_id,
                    attention, name,
                    street_address,
                    street_address2,
                    city,
                    state,
                    zip_code,
                    country,
                    phone,
                    mobile_phone,
                    fax,
                    email,
                    notes,
                    credit_card_holder_name,
                    credit_card_number,
                    credit_card_expiry_month,
                    credit_card_expiry_year,
                    custom_field1,
                    custom_field2,
                    custom_field3,
                    custom_field4,
                    enabled)
            VALUES (
                    :domain_id,
                    :attention,
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
                    :notes,
                    :credit_card_holder_name,
                    :credit_card_number,
                    :credit_card_expiry_month,
                    :credit_card_expiry_year,
                    :custom_field1,
                    :custom_field2,
                    :custom_field3,
                    :custom_field4,
                    :enabled)";

    return dbQuery($sql,
                    ':attention'               , $attention,
                    ':name'                    , $name,
                    ':street_address'          , $street_address,
                    ':street_address2'         , $street_address2,
                    ':city'                    , $city,
                    ':state'                   , $state,
                    ':zip_code'                , $zip_code,
                    ':country'                 , $country,
                    ':phone'                   , $phone,
                    ':mobile_phone'            , $mobile_phone,
                    ':fax'                     , $fax,
                    ':email'                   , $email,
                    ':notes'                   , $notes,
                    ':credit_card_holder_name' , $credit_card_holder_name,
                    ':credit_card_number'      , $encrypted_credit_card_number,
                    ':credit_card_expiry_month', $credit_card_expiry_month,
                    ':credit_card_expiry_year' , $credit_card_expiry_year,
                    ':custom_field1'           , $custom_field1,
                    ':custom_field2'           , $custom_field2,
                    ':custom_field3'           , $custom_field3,
                    ':custom_field4'           , $custom_field4,
                    ':enabled'                 , $enabled,
                    ':domain_id'               , $domain_id);
    // @formatter:on
}

function getInvoices(&$sth) {
    global $config;
    $invoice = NULL;

    if ($invoice = $sth->fetch()) {
        $invoice['calc_date'] = date('Y-m-d', strtotime($invoice['date']));
        $invoice['date'] = siLocal::date($invoice['date']);
        $invoice['total'] = getInvoiceTotal($invoice['id']);
        $invoice['paid'] = calc_invoice_paid($invoice['id']);
        $invoice['owing'] = $invoice['total'] - $invoice['paid'];
    }
    return $invoice;
}

function getCustomerInvoices($id, $domain_id = '') {
    global $config;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT
                iv.id,
                iv.index_id,
                iv.date,
                iv.type_id,
                (SELECT SUM( COALESCE(ii.total, 0))
                 FROM " . TB_PREFIX . "invoice_items ii
                 WHERE ii.invoice_id = iv.id
                   AND ii.domain_id  = iv.domain_id) AS invd,
                (SELECT SUM( COALESCE(ap.ac_amount, 0))
                 FROM " . TB_PREFIX . "payment ap
                 WHERE ap.ac_inv_id = iv.id
                   AND ap.domain_id = iv.domain_id) AS pmt,
                (SELECT COALESCE(invd, 0)) AS total,
                (SELECT COALESCE(pmt, 0)) AS paid,
                (SELECT (total - paid)) AS owing,
                pr.status,
                pr.pref_inv_wording
            FROM " . TB_PREFIX . "invoices iv
            LEFT JOIN " . TB_PREFIX . "preferences pr
                ON (pr.pref_id   = iv.preference_id
                AND pr.domain_id = iv.domain_id)
            WHERE iv.customer_id = :id
              AND iv.domain_id   = :domain_id
            ORDER BY iv.id DESC;";
    // @formatter:on
    $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);

    $invoices = NULL;
    while ($invoice = $sth->fetch()) {
        $invoice['calc_date'] = date('Y-m-d', strtotime($invoice['date']));
        $invoice['date'] = siLocal::date($invoice['date']);
        $invoices[] = $invoice;
    }
    return $invoices;
}

function getCustomers($domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT * FROM " . TB_PREFIX . "customers WHERE domain_id = :domain_id";
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    $customers = array();
    for ($i = 0; $customer = $sth->fetch(); $i++) {
        // @formatter:off
        $customer['enabled'] = ($customer['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
        $customer['total']   = calc_customer_total($customer['id']);
        $customer['paid']    = calc_customer_paid($customer['id']);
        $customer['owing']   = $customer['total'] - $customer['paid'];
        $customers[$i]       = $customer;
        // @formatter:on
    }

    return $customers;
}

function getActiveCustomers($domain_id = '') {
    global $db_server;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    if ($db_server == 'pgsql') {
        $sql = "SELECT * FROM " . TB_PREFIX . "customers
                WHERE enabled and domain_id = :domain_id ORDER BY name";
    } else {
        $sql = "SELECT * FROM " . TB_PREFIX . "customers
                WHERE enabled != 0 and domain_id = :domain_id ORDER BY name";
    }
    // @formatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);

    return $sth->fetchAll();
}

function insertTaxRate($domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "INSERT into " . TB_PREFIX . "tax (
                    domain_id,
                    tax_description,
                    tax_percentage,
                    type,
                    tax_enabled)
            VALUES (
                    :domain_id,
                    :description,
                    :percent,
                    :type,
                    :enabled)";

    if (!(dbQuery($sql,
                    ':domain_id'  , $domain_id,
                    ':description', $_POST['tax_description'],
                    ':percent'    , $_POST['tax_percentage'],
                    ':type'       , $_POST['type'],
                    ':enabled'    , $_POST['tax_enabled']))) {
        return $LANG['save_tax_rate_failure'];
    }
    // @formatter:on
    return $LANG['save_tax_rate_success'];
}

function updateTaxRate($domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "tax
            SET tax_description = :description,
                tax_percentage  = :percentage,
                type            = :type,
                tax_enabled     = :enabled
            WHERE tax_id    = :id
              AND domain_id = :domain_id";

    if (!(dbQuery($sql,
                    ':description', $_POST['tax_description'],
                    ':percentage' , $_POST['tax_percentage'],
                    ':enabled'    , $_POST['tax_enabled'],
                    ':id'         , $_GET['id'],
                    ':domain_id'  , $domain_id,
                    ':type'       , $_POST['type']))) {
        return $LANG['save_tax_rate_failure'];
    }
    // @formatter:on
    return $LANG['save_tax_rate_success'];
}

/**
 * Ensure that there is a time value in the datetime object.
 *
 * @param string $in_date Datetime string in the format, "YYYY/MM/DD HH:MM:SS".
 *        Note: If time part is "00:00:00" it will be set to the current time.
 * @return string Datetime string with time set.
 */
function sqlDateWithTime($in_date) {
    list ($date, $time) = explode(' ', $in_date);
    if (!$time or $time == '00:00:00') {
        $time = date('H:i:s');
    }
    $out_date = "$date $time";
    return $out_date;
}

function insertInvoice($type, $domain_id = '') {
    global $db_server;
    $domain_id = domain_id::get($domain_id);

    if ($db_server == 'mysql' &&
                     !_invoice_check_fk($_POST['biller_id'], $_POST['customer_id'], $type, $_POST['preference_id'])) {
        return NULL;
    }

    $pref_group = getPreference($_POST[preference_id]);

    // also set the current time (if NULL or =00:00:00)
    $clean_date = sqlDateWithTime($_POST['date']);

    // @formatter:off
    switch ($db_server) {
        case 'pgsql':
            $sql = "INSERT INTO " . TB_PREFIX . "invoices (
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
                    VALUES (
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
            break;

        default:
            $sql = "INSERT INTO " . TB_PREFIX . "invoices (
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
                    VALUES (
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
            break;
    }

    // @formatter:off
    $sth = dbQuery( $sql,
                    ':index_id'     , index::next('invoice', $pref_group['index_group'], $domain_id),
                    ':domain_id'    , $domain_id,
                    ':biller_id'    , $_POST['biller_id'],
                    ':customer_id'  , $_POST['customer_id'],
                    ':type'         , $type,
                    ':preference_id', $_POST['preference_id'],
                    ':date'         , $clean_date,
                    ':note'         , trim($_POST['note']),
                    ':customField1' , $_POST['customField1'],
                    ':customField2' , $_POST['customField2'],
                    ':customField3' , $_POST['customField3'],
                    ':customField4' , $_POST['customField4']
                  );
    // @formatter:on

    // Needed only if si_index table exists
    index::increment('invoice', $pref_group[index_group], $domain_id);

    return $sth;
}

function updateInvoice($invoice_id, $domain_id = '') {
    global $db_server;

    $domain_id = domain_id::get($domain_id);

    $invoiceobj = new invoice();
    $current_invoice = $invoiceobj->select($_POST['id']);
    $current_pref_group = getPreference($current_invoice['preference_id']);

    $new_pref_group = getPreference($_POST['preference_id']);

    $index_id = $current_invoice['index_id'];

    if ($current_pref_group['index_group'] != $new_pref_group['index_group']) {
        $index_id = index::increment('invoice', $new_pref_group['index_group']);
    }

    if ($db_server == 'mysql' &&
                     !_invoice_check_fk($_POST['biller_id'], $_POST['customer_id'], $type, $_POST['preference_id'])) {
        return NULL;
    }

    // @formatter:off
    $sql = "UPDATE  " . TB_PREFIX . "invoices
            SET index_id      = :index_id,
                biller_id     = :biller_id,
                customer_id   = :customer_id,
                preference_id = :preference_id,
                date          = :date,
                note          = :note,
                custom_field1 = :customField1,
                custom_field2 = :customField2,
                custom_field3 = :customField3,
                custom_field4 = :customField4
            WHERE id        = :invoice_id AND
                  domain_id = :domain_id";
    return dbQuery( $sql,
                    ':index_id'     , $index_id,
                    ':biller_id'    , $_POST['biller_id'],
                    ':customer_id'  , $_POST['customer_id'],
                    ':preference_id', $_POST['preference_id'],
                    ':date'         , $_POST['date'],
                    ':note'         , trim($_POST['note']),
                    ':customField1' , (isset($_POST['customField1']) ? $_POST['customField1'] : ''),
                    ':customField2' , (isset($_POST['customField2']) ? $_POST['customField2'] : ''),
                    ':customField3' , (isset($_POST['customField3']) ? $_POST['customField3'] : ''),
                    ':customField4' , (isset($_POST['customField4']) ? $_POST['customField4'] : ''),
                    ':invoice_id'   , $invoice_id,
                    ':domain_id'    , $domain_id);
    // @formatter:on
}

function insertInvoiceItem($invoice_id, $quantity, $product_id, $line_number, $line_item_tax_id, $description = "",
                $unit_price = "", $attribute = "", $domain_id = '') {
    global $logger;
    global $db_server;
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    // do taxes
    $attr = array();
    // $logger->log('Line item attributes: ' . var_export($attribute, true), Zend_Log::INFO);
    foreach ($attribute as $k => $v) {
        if ($attribute[$v] !== '') {
            $attr[$k] = $v;
        }
    }

    $tax_total = getTaxesPerLineItem($line_item_tax_id, $quantity, $unit_price, $domain_id);

    /*
     * $logger->log(' ', Zend_Log::INFO);
     * $logger->log(' ', Zend_Log::INFO);
     * $logger->log('Invoice: ' . $invoice_id . ' Tax ' . $line_item_tax_id . ' for line item ' . $line_number . ': ' .
     * $tax_total, Zend_Log::INFO);
     * $logger->log('Description: ' . $description, Zend_Log::INFO);
     * $logger->log(' ', Zend_Log::INFO);
     */

    // line item gross total
    $gross_total = $unit_price * $quantity;

    // line item total
    $total = $gross_total + $tax_total;

    // Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
    if ($description == $LANG['description']) {
        $description = "";
    }

    if ($db_server == 'mysql' && !_invoice_items_check_fk($invoice_id, $product_id, $tax['tax_id'])) {
        return NULL;
    }

    // @formatter:off
    $sql = "INSERT INTO " . TB_PREFIX . "invoice_items (
                    invoice_id,
                    domain_id,
                    quantity,
                    product_id,
                    unit_price,
                    tax_amount,
                    gross_total,
                    description,
                    total,
                    attribute)
            VALUES (:invoice_id,
                    :domain_id,
                    :quantity,
                    :product_id,
                    :unit_price,
                    :tax_amount,
                    :gross_total,
                    :description,
                    :total,
                    :attribute)";
    dbQuery($sql,   ':invoice_id' , $invoice_id,
                    ':domain_id'  , $domain_id,
                    ':quantity'   , $quantity,
                    ':product_id' , $product_id,
                    ':unit_price' , $unit_price,
                    ':tax_amount' , $tax_total,
                    ':gross_total', $gross_total,
                    ':description', trim($description),
                    ':total'      , $total,
                    ':attribute'  , json_encode($attr));

    invoice_item_tax(lastInsertId(), $line_item_tax_id, $unit_price, $quantity, "insert");
    // TODO fix this
    return true;
}

/**
 * Calculate the total tax for the line item
 * @param array $line_item_tax_id Tax values to apply.
 * @param int $quantity Number of units.
 * @param int $unit_price Price of each unit.
 * @param string $domain_id SI domain being processed.
 */
function getTaxesPerLineItem($line_item_tax_id, $quantity, $unit_price, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);

    $tax_total = 0;
    foreach ($line_item_tax_id as $key => $value) {
        $tax = getTaxRate($value, $domain_id);
        $tax_amount = lineItemTaxCalc($tax, $unit_price, $quantity);
        $tax_total = $tax_total + $tax_amount;
    }
    return $tax_total;
}

/**
 * Calculate the total tax for this line item.
 * @param array $tax Taxes for the line item.
 * @param int $unit_price Price for each unit.
 * @param int $quantity Number of units to tax.
 * @return Total tax for the line item.
 */
function lineItemTaxCalc($tax, $unit_price, $quantity) {
    // Calculate tax as a percentage of unit price or dollars per unit.
    if ($tax['type'] == "%") {
        $tax_amount = (($tax['tax_percentage'] / 100) * $unit_price) * $quantity;
    } else {
        $tax_amount = $tax['tax_percentage'] * $quantity;
    }

    return $tax_amount;
}

/*
 * Function: invoice_item_tax
 * Purpose: insert/update the multiple taxes per line item into the si_invoice_item_tax table
 */
function invoice_item_tax($invoice_item_id, $line_item_tax_id, $unit_price, $quantity, $action = '', $domain_id = '') {
    $domain_id = domain_id::get($domain_id);

    // if editing invoice delete all tax info then insert first then do insert again
    // probably can be done without delete - someone to look into this if required - TODO
    if ($action == "update") {
        // @formatter:off
        $sql_delete = "DELETE from " . TB_PREFIX . "invoice_item_tax
                       WHERE invoice_item_id = :invoice_item_id";
        // @formatter:on
        dbQuery($sql_delete, ':invoice_item_id', $invoice_item_id);
    }

    foreach ($line_item_tax_id as $key => $value) {
        if ($value !== "") {
            $tax = getTaxRate($value, $domain_id);

            $tax_amount = lineItemTaxCalc($tax, $unit_price, $quantity);

            // @formatter:off
            $sql = "INSERT INTO " . TB_PREFIX . "invoice_item_tax (
                            invoice_item_id,
                            tax_id,
                            tax_type,
                            tax_rate,
                            tax_amount)
                    VALUES (:invoice_item_id,
                            :tax_id,
                            :tax_type,
                            :tax_rate,
                            :tax_amount)";
            dbQuery($sql, ':invoice_item_id', $invoice_item_id,
                          ':tax_id'         , $tax['tax_id'],
                          ':tax_type'       , $tax['type'],
                          ':tax_rate'       , $tax['tax_percentage'],
                          ':tax_amount'     , $tax_amount);
            // @formatter:on
        }
    }
    // TODO fix this
    return true;
}

/**
 * Update invoice_items table for a specific entry.
 * @param int $id Unique id for the record to be updated.
 * @param int $quantity Number of items
 * @param int $product_id Unique id of the si_products record for this item.
 * @param int $line_number Line item number for the position on the invoice.
 * @param int $line_item_tax_id Unique id for the taxes to apply to this line item.
 * @param string $description Extended description for this line item.
 * @param decimal $unit_price Price of each unit of this item.
 * @param string $attribute (Optional) Attributes for invoice.
 * @param int $domain_id (Optional) Domain ID number for this entry.
 * @return boolean true always returned.
 */
function updateInvoiceItem($id, $quantity, $product_id, $line_number, $line_item_tax_id, $description, $unit_price,
                $attribute = "", $domain_id = '') {
    global $LANG;
    global $db_server;

    $domain_id = domain_id::get($domain_id);

    $attr = array();
    foreach ($attribute as $k => $v) {
        if ($attribute[$v] !== '') {
            $attr[$k] = $v;
        }
    }

    $tax_total = getTaxesPerLineItem($line_item_tax_id, $quantity, $unit_price);

    // line item gross total
    $gross_total = $unit_price * $quantity;

    // line item total
    $total = $gross_total + $tax_total;

    // Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
    if ($description == $LANG['description']) {
        $description = "";
    }

    if ($db_server == 'mysql' && !_invoice_items_check_fk(null, $product_id, $tax_id, 'update')) {
        return NULL;
    }

    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "invoice_items
            SET quantity    =  :quantity,
                product_id  = :product_id,
                unit_price  = :unit_price,
                tax_amount  = :tax_amount,
                gross_total = :gross_total,
                description = :description,
                total       = :total,
                attribute   = :attribute
            WHERE id = :id AND domain_id = :domain_id";
    dbQuery($sql, ':quantity'   , $quantity,
                  ':product_id' , $product_id,
                  ':unit_price' , $unit_price,
                  ':tax_amount' , $tax_total,
                  ':gross_total', $gross_total,
                  ':description', $description,
                  ':total'      , $total,
                  ':attribute'  , json_encode($attr),
                  ':id'         , $id,
                  ':domain_id'  , $domain_id);
    // @formatter:on

    // if from a new invoice item in the edit page user lastInsertId()
    ($id == NULL) ? $id = lastInsertId() : $id = $id;

    invoice_item_tax($id, $line_item_tax_id, $unit_price, $quantity, "update");

    // TODO: Fix to send a real result (possibly throw an error
    return true;
}

/**
 * Attempts to delete rows from the database.
 * Currently allows for deletion of invoices, invoice_items, and products entries.
 * All other $module values will fail. $idField is also checked on a per-table
 * basis, i.e. invoice_items can be either "id" or "invoice_id" while products
 * can only be "id". Invalid $module or $idField values return false, as do
 * calls that would fail foreign key checks.
 * @param string $module Table a row
 * @param unknown $idField
 * @param unknown $id
 * @param string $domain_id
 * @return false if failure otherwise result of dbQuery().
 */
function delete($module, $idField, $id, $domain_id = '') {
    global $dbh;
    global $logger;
    $domain_id = domain_id::get($domain_id);

    $has_domain_id = false;

    $lctable = strtolower($module);
    $s_idField = ''; // Presetting the whitelisted column to fail

    // SC: $valid_tables contains the base names of all tables that can have rows
    // deleted using this function. This is used for whitelisting deletion targets.
    $valid_tables = array('invoices', 'invoice_items', 'invoice_item_tax', 'products');

    if (in_array($lctable, $valid_tables)) {
        // A quick once-over on the dependencies of the possible tables
        if ($lctable == 'invoice_item_tax') {
            // Not required by any FK relationships
            if (!in_array($idField, array('invoice_item_id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } elseif ($lctable == 'invoice_items') {
            // Not required by any FK relationships
            if (!in_array($idField, array('id', 'invoice_id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } elseif ($lctable == 'products') {
            $has_domain_id = true;
            // Check for use of product
            // @formatter:off
            $sth = $dbh->prepare('SELECT count(*) FROM ' . TB_PREFIX . 'invoice_items
                                  WHERE product_id = :id AND domain_id = :domain_id');
            // @formatter:on
            $sth->execute(array(':id' => $id, ':domain_id', $domain_id));
            $ref = $sth->fetch();
            if ($sth->fetchColumn() != 0) {
                return false; // Fail, product still in use
            }
            $sth = NULL;

            if (!in_array($idField, array('id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } elseif ($lctable == 'invoices') {
            $has_domain_id = true;
            // Check for existant payments and line items
            // @formatter:off
            $sth = $dbh->prepare('SELECT count(*)
                                  FROM (SELECT id FROM ' . TB_PREFIX . 'invoice_items
                                        WHERE invoice_id = :id AND domain_id = :domain_id
                                          UNION ALL
                                        SELECT id FROM ' . TB_PREFIX . 'payment
                                        WHERE ac_inv_id = :id AND domain_id = :domain_id) x');
            // @formatter:on
            $sth->execute(array(':id' => $id, ':domain_id', $domain_id));
            if ($sth->fetchColumn() != 0) {
                return false; // Fail, line items or payments still exist
            }
            $sth = NULL;

            // SC: Later, may accept other values for $idField
            if (!in_array($idField, array('id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } else {
            return false; // Fail, no checks for this table exist yet
        }
    } else {
        return false; // Fail, invalid table name
    }

    if ($s_idField == '') {
        return false; // Fail, column whitelisting not performed
    }

    // Tablename and column both pass whitelisting and FK checks
    $sql = "DELETE FROM " . TB_PREFIX . "$module WHERE $s_idField = :id";
    if ($has_domain_id) {
        $sql .= " AND domain_id = :domain_id";
        return dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
    }
    return dbQuery($sql, ':id', $id);
}

/**
 * Retrieve maximum invoice number assigned.
 * @param string $domain_id
 * @return Maximum invoice number assigned.
 */
function maxInvoice($domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    $sql = "SELECT max(id) as maxId FROM " . TB_PREFIX . "invoices WHERE domain_id = :domain_id";
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    return $sth->fetch();
}

/**
 * Test for database table existing.
 * @param string $table Table to check for.
 * @return true if specified table exists, false otherwise.
 */
function checkTableExists($table) {
    global $pdoAdaptor;

    switch ($pdoAdaptor) {
        case "pgsql":
            $sql = 'SELECT 1 FROM pg_tables WHERE tablename = ' . $table . ' LIMIT 1';
            break;

        case "sqlite":
            $sql = 'SELECT * FROM ' . $table . 'LIMIT 1';
            break;

        case "mysql":
        default:
            $sql = "SHOW TABLES LIKE '" . $table . "'";
            break;
    }

    $sth = dbQuery($sql);
    if ($sth !== false && $sth->fetchAll()) {
        return true;
    }
    return false;
}

function checkFieldExists($table, $field) {
    global $dbh;
    global $pdoAdapter;

    if ($pdoAdapter == 'pgsql') {
        // @formatter:off
        $sql = "SELECT 1 FROM pg_attribute a
                INNER JOIN pg_class c ON (a.attrelid = c.oid)
                WHERE c.relkind = 'r'
                  AND c.relname = :table
                  AND a.attname = :field
                  AND NOT a.attisdropped
                  AND a.attnum > 0
                LIMIT 1";
        // @formatter:on
    } else {
        // @formatter:off
        $sql = "SELECT 1 FROM information_schema.columns
                WHERE column_name = :field AND table_name = :table LIMIT 1";
        // @formatter:on
    }

    $sth = $dbh->prepare($sql);
    if ($sth && $sth->execute(array(':field' => $field, ':table' => $table))) {
        if ($sth->fetch()) return true;
    }
    return false;
}

/**
 * Get a list of fields (aka columns) in a specified table.
 * @param string $table Name of the table to get fields for.
 *        Note: <b>TB_PREFIX</b> will be added if not present.
 * @return array Column names from the table. An empty array is
 *         returned if no columns found.
 */
function getTableFields($table_in) {
    global $dbh;
    global $pdoAdapter;

    $pattern = '/^' . TB_PREFIX . '/';
    if (!preg_match($pattern, $table_in)) {
        $table = TB_PREFIX . $table_in;
    } else {
        $table = $table_in;
    }
    if ($pdoAdapter == 'pgsql') {
        // @formatter:off
        $sql = "SELECT column_name FROM pg_attribute a
                INNER JOIN pg_class c ON (a.attrelid = c.oid)
                WHERE c.relkind = 'r'
                  AND c.relname = :table
                  AND NOT a.attisdropped
                  AND a.attnum > 0";
        // @formatter:on
    } else {
        // @formatter:off
        $sql = "SELECT column_name FROM information_schema.columns
                WHERE table_name = :table";
        // @formatter:on
    }

    $columns = array();
    if ($sth = $dbh->prepare($sql)) {
        if ($sth->execute(array(':table' => $table))) {
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row['column_name'];
            }
        }
    }
    return $columns;
}

function getURL() {
    global $config;

    $port = "";
    $dir = dirname($_SERVER['PHP_SELF']);
    // remove incorrect slashes for WinXP etc.
    $dir = str_replace('\\', '', $dir);

    // set the port of http(s) section
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $_SERVER['FULL_URL'] = "https://";
    } else {
        $_SERVER['FULL_URL'] = "http://";
    }

    $_SERVER['FULL_URL'] .= $config->authentication->http . $_SERVER['HTTP_HOST'] . $dir;

    return $_SERVER['FULL_URL'];
}

function sql2array($strSql) {
    global $dbh;
    $sqlInArray = NULL;

    $result_strSql = dbQuery($strSql);

    for ($i = 0; $sqlInRow = PDOStatement::fetchAll($result_strSql); $i++) {
        $sqlInArray[$i] = $sqlInRow;
    }
    return $sqlInArray;
}

/**
 * Calculate number of patches applied.
 * This is used to determine the database structure and by that
 * the features that are available.
 * @return Number of patches applied to the database.
 */
function getNumberOfDoneSQLPatches() {
    $check_patches_sql = "SELECT count(sql_patch) AS count FROM " . TB_PREFIX . "sql_patchmanager ";
    $sth = dbQuery($check_patches_sql);
    $patches = $sth->fetch();
    return $patches['count'];
}

function pdfThis($html, $file_location = "", $pdfname) {
    global $config;

    // set_include_path("../../../../library/pdf/");
    require_once ('./library/pdf/config.inc.php');
    require_once ('./library/pdf/pipeline.factory.class.php');
    require_once ('./library/pdf/pipeline.class.php');
    parse_config_file('./library/pdf/html2ps.config');

    require_once ("./include/init.php"); // for getInvoice() and getPreference()

    if (!function_exists('convert_to_pdf')) {

        /**
         * Runs the HTML->PDF conversion with default settings
         *
         * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
         * use absolute links (like http://my.host/image.gif).
         *
         * @param $path_to_html String path to source html file.
         * @param $path_to_pdf String path to file to save generated PDF to.
         */
        function convert_to_pdf($html_to_pdf, $pdfname, $file_location = "") {
            global $config;

            $destination = $file_location == "download" ? "DestinationDownload" : "DestinationFile";
            // Handles the saving generated PDF to user-defined output file on server
            if (!class_exists('MyFetcherLocalFile')) {
                class MyFetcherLocalFile extends Fetcher {
                    var $_content;

                    function MyFetcherLocalFile($html_to_pdf) {
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

            $pipeline = PipelineFactory::create_default_pipeline("", ""); // Attempt to auto-detect encoding

            // Override HTML source
            $pipeline->fetchers[] = new MyFetcherLocalFile($html_to_pdf);

            $baseurl = "";
            $media = Media::predefined($config->export->pdf->papersize);
            $media->set_landscape(false);

            // @formatter:off
            $margins = array('left'   => $config->export->pdf->leftmargin,
                             'right'  => $config->export->pdf->rightmargin,
                             'top'    => $config->export->pdf->topmargin,
                             'bottom' => $config->export->pdf->bottommargin);

            global $g_config;
            $g_config = array('cssmedia'                => 'screen',
                              'renderimages'            => true,
                              'renderlinks'             => true,
                              'renderfields'            => true,
                              'renderforms'             => false,
                              'mode'                    => 'html',
                              'encoding'                => '',
                              'debugbox'                => false,
                              'pdfversion'              => '1.4',
                              'process_mode'            => 'single',
                              'pixels'                  => $config->export->pdf->screensize,
                              'media'                   => $config->export->pdf->papersize,
                              'margins'                 => $margins,
                              'transparency_workaround' => 1,
                              'imagequality_workaround' => 1,
                              'draw_page_border'        => false);
            // @formatter:on

            $media->set_margins($g_config['margins']);
            $media->set_pixels($config->export->pdf->screensize);

            global $g_px_scale;
            $g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;

            global $g_pt_scale;
            $g_pt_scale = $g_px_scale * (72 / 96);

            $pipeline->configure($g_config);
            $pipeline->data_filters[] = new DataFilterUTF8("");
            $pipeline->destination = new $destination($pdfname);
            $pipeline->process($baseurl, $media);
        }
    }

    convert_to_pdf($html, $pdfname, $file_location);
}

function getNumberOfDonePatches() {
    $check_patches_sql = "SELECT max(sql_patch_ref) AS count FROM " . TB_PREFIX . "sql_patchmanager ";
    $sth = dbQuery($check_patches_sql);

    $patches = $sth->fetch();

    // Returns number of patches applied
    return $patches['count'];
}

function getNumberOfPatches() {
    global $patch;
    $patches = getNumberOfDonePatches();
    $patch_count = max(array_keys($patch));
    return $patch_count - $patches;
}

function runPatches() {
    global $patch;
    global $db_server;
    global $dbh;

    $display_block = "";
    $sql = "SHOW TABLES LIKE '" . TB_PREFIX . "sql_patchmanager'";
    if ($db_server == 'pgsql') {
        $sql = "SELECT 1 FROM pg_tables WHERE tablename ='" . TB_PREFIX . "sql_patchmanager'";
    }
    $sth = dbQuery($sql);
    $rows = $sth->fetchAll();

    $smarty_datas = array();

    if (count($rows) == 1) {
        if ($db_server == 'pgsql') {
            $dbh->beginTransaction();
        }

        for ($i = 0; $i < count($patch); $i++) {
            $smarty_datas['rows'][$i] = run_sql_patch($i, $patch[$i]);
        }

        if ($db_server == 'pgsql') {
            $dbh->commit();
        }

        $smarty_datas['message'] = "The database patches have now been applied. You can now start working with SimpleInvoices";
        $smarty_datas['html']    = "<div class='si_toolbar si_toolbar_form'><a href='index.php'>HOME</a></div>";
        $smarty_datas['refresh'] = 5;
    } else {
        $smarty_datas['html']  = "Step 1 - This is the first time Database Updates has been run";
        $smarty_datas['html'] .= initialise_sql_patch();
        $smarty_datas['html'] .= "<br />
        Now that the Database upgrade table has been initialised,
        please go back to the Database Upgrade Manger page by clicking
        the following button to run the remaining patches.
        <div class='si_toolbar si_toolbar_form'>
            <a href='index.php?module=options&amp;view=database_sqlpatches'>Continue</a>
        </div>
        .";
    }

    global $smarty;
    $smarty->assign("page", $smarty_datas);
}

function donePatches() {
    $smarty_datas['message'] = "The database patches are up to date. You can continue working with SimpleInvoices";
    $smarty_datas['html']    = "<div class='si_toolbar si_toolbar_form'><a href='index.php'>HOME</a></div>";
    $smarty_datas['refresh'] = 3;
    global $smarty;
    $smarty->assign("page", $smarty_datas);
}

function listPatches() {
    global $patch;

    $smarty_datas = array();
    $smarty_datas['message'] = "Your version of SimpleInvoices can now be upgraded. With this new release there are database patches that need to be applied";
    $smarty_datas['html']    = <<<EOD
    <p>The list below describes which patches have and have not been applied to the database,
       the aim is to have them all applied.
       <br />
       If there are patches that have not been applied to the SimpleInvoices database,
       please run the Update database by clicking update.
    </p>
    <div class="si_message_warning">Warning: Please backup your database before upgrading!</div>
    <div class="si_toolbar si_toolbar_form">
        <a href="./index.php?case=run" class="">
        <img src="./images/common/tick.png" alt="" />Update</a>
    </div>
EOD;

    for ($p = 0; $p < count($patch); $p++) {
        $patch_name = htmlsafe($patch[$p]['name']);
        $patch_date = htmlsafe($patch[$p]['date']);
        if (check_sql_patch($p, $patch[$p]['name'])) {
            $smarty_datas['rows'][$p]['text'] = "SQL patch $p, $patch_name <i>has</i> already been applied in release $patch_date";
            $smarty_datas['rows'][$p]['result'] = 'skip';
        } else {
            $smarty_datas['rows'][$p]['text'] = "SQL patch $p, $patch_name <b>has not</b> been applied to the database";
            $smarty_datas['rows'][$p]['result'] = 'todo';
        }
    }

    global $smarty;
    $smarty->assign("page", $smarty_datas);
}

function check_sql_patch($check_sql_patch_ref, $check_sql_patch_field) {
    $sql = "SELECT * FROM " . TB_PREFIX . "sql_patchmanager WHERE sql_patch_ref = :patch";
    $sth = dbQuery($sql, ':patch', $check_sql_patch_ref);
    if (count($sth->fetchAll()) > 0) {
        return true;
    }
    return false;
}

function run_sql_patch($id, $patch) {
    global $dbh;
    global $db_server;
    $display_block = "";

    $sql = "SELECT * FROM " . TB_PREFIX . "sql_patchmanager WHERE sql_patch_ref = :id";
    $sth = dbQuery($sql, ':id', $id);

    $escaped_id = htmlsafe($id);
    $patch_name = htmlsafe($patch['name']);

    $smarty_row = array();
    if (count($sth->fetchAll()) != 0) {
        // forget about the patch as it has already been run!!
        $smarty_row['text'] = "Skipping SQL patch $escaped_id, $patch_name as it <i>has</i> already been applied";
        $smarty_row['result'] = "skip";
    } else {
        // patch hasn't been run, so run it
        dbQuery($patch['patch']);

        $smarty_row['text'] = "SQL patch $escaped_id, $patch_name <i>has</i> been applied to the database";
        $smarty_row['result'] = "done";

        // now update the ".TB_PREFIX."sql_patchmanager table
        // @formatter:off
        $sql = "INSERT INTO " . TB_PREFIX . "sql_patchmanager (
                        sql_patch_ref,
                        sql_patch,
                        sql_release,
                        sql_statement)
                VALUES (:id, :name, :date, :patch)";
        dbQuery($sql,   ':id'   , $id,
                        ':name' , $patch['name'],
                        ':date' , $patch['date'],
                        ':patch', $patch['patch']);
        // @formatter:on
        if ($id == 126) {
            patch126();
        }

        /*
         * cusom_fields to new customFields patch - commented out till future
         */
        /*
         * elseif($id == 137) {
         * convertInitCustomFields();
         * }
         */
    }
    return $smarty_row;
}

function initialise_sql_patch() {
    // SC: MySQL-only function, not porting to PostgreSQL

    // check sql patch 1
    // @formatter:off
    $sql_patch_init = "CREATE TABLE " . TB_PREFIX . "sql_patchmanager (
                           sql_id        INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
                           sql_patch_ref VARCHAR( 50) NOT NULL,
                           sql_patch     VARCHAR(255) NOT NULL,
                           sql_release   VARCHAR( 25) NOT NULL,
                           sql_statement TEXT         NOT NULL)
                       TYPE = MYISAM ";
    // @formatter:on
    dbQuery($sql_patch_init);

    $log = "Step 2 - The SQL patch table has been created<br />";

    echo $display_block;
    // @formatter:off
    $sql_insert = "INSERT INTO " . TB_PREFIX . "sql_patchmanager (
                       sql_id,
                       sql_patch_ref,
                       sql_patch,
                       sql_release,
                       sql_statement)
                   VALUES (
                       '',
                       '1',
                       'Create " . TB_PREFIX . "sql_patchmanger table',
                       '20060514',
                       :patch)";
    // @formatter:on
    dbQuery($sql_insert, ':patch', $sql_patch_init);

    $log .= "Step 3 - The SQL patch has been inserted into the SQL patch table<br />";

    return $log;
}

function patch126() {
    // SC: MySQL-only function, not porting to PostgreSQL
    $sql = "SELECT * FROM " . TB_PREFIX . "invoice_items WHERE product_id = 0";
    $sth = dbQuery($sql);

    while ($res = $sth->fetch()) {
        // @formatter:off
        $sql = "INSERT INTO " . TB_PREFIX . "products (
                        id,
                        description,
                        unit_price,
                        enabled,
                        visible)
                VALUES (NULL,
                        :description,
                        :gross_total,
                        '0',
                        '0')";
        dbQuery($sql, ':description', $res[description],
                      ':total'      , $res[gross_total]);
        $id = lastInsertId();

        $sql = "UPDATE  " . TB_PREFIX . "invoice_items
                SET product_id = :id,
                    unit_price = :price
                WHERE " . TB_PREFIX . "invoice_items.id = :item";
        dbQuery($sql, ':id', $id[0], ':price', $res[gross_total], ':item', $res[id]);
        // @formatter:on
    }
}

function convertInitCustomFields() {
    // This function is exactly the same as convertCustomFields() in
    // ./include/customFieldConversion.php but without the
    // print_r and echo output while storing

    /* check if any value set -> keeps all data for sure */
    global $dbh;
    $domain_id = domain_id::get();

    $sql = "SELECT * FROM " . TB_PREFIX . "custom_fields WHERE domain_id = :domain_id";
    $sth = $dbh->prepare($sql, ':domain_id', $domain_id);
    $sth->execute();

    while ($custom = $sth->fetch()) {
        if (preg_match("/(.+)_cf([1-4])/", $custom['cf_custom_field'], $match)) {
            switch ($match[1]) {
                case "biller":
                    $cat = 1;
                    break;
                case "customer":
                    $cat = 2;
                    break;
                case "product":
                    $cat = 3;
                    break;
                case "invoice":
                    $cat = 4;
                    break;
                default:
                    $case = 0;
            }

            $cf_field = "custom_field" . $match[2];
            $sql = "SELECT id, :field FROM :table WHERE domain_id = :domain_id";
            $tablename = TB_PREFIX . $match[1];

            // Only biller table is singular, products, invoices and customers tables are all plural
            if ($match[1] != "biller") {
                $tablename .= "s";
            }

            $store = false;

            // If custom field name is set
            if ($custom['cf_custom_label'] != NULL) {
                $store = true;
            }

            $tth = $dbh->prepare($sql);
            $tth->bindValue(':table', $tablename);
            $tth->bindValue(':field', $cf_field);
            $tth->bindValue(':domain_id', $domain_id);
            $tth->execute();

            // If any field is set, create custom field
            while ($res = $tth->fetch()) {
                if ($res[1] != NULL) {
                    $store = true;
                    break;
                }
            }

            if ($store) {
                // create new text custom field
                saveInitCustomField(3, $cat, $custom['cf_custom_field'], $custom['cf_custom_label']);
                $id = lastInsertId();

                $plugin = getPluginById(3);
                $plugin->setFieldId($id);

                // insert all data
                $uth = $dbh->prepare($sql);
                $uth->bindValue(':table', $tablename);
                $uth->bindValue(':field', $cf_field);
                $uth->bindValue(':domain_id', $domain_id);
                $uth->execute();
                while ($res2 = $uth->fetch()) {
                    $plugin->saveInput($res2[$cf_field], $res2['id']);
                }
            }
        }
    }
}

function saveInitCustomField($id, $category, $name, $description) {
    // This function is exactly same as saveCustomField() in ./include/manageCustomFields.php
    // but without the final echo output
    $sql = "INSERT INTO " . TB_PREFIX . "customFields  (pluginId, categorieId, name, description)
            VALUES (:id, :category, :name, :description)";
    dbQuery($sql, ':id', $id, ':category', $category, ':name', $name, ':description', $description);
}

/**
 * Function: get_custom_field_label
 *
 * Prints the name of the custom field based on the input. If the custom field has not been defined by the user than use
 * the default in the lang files
 *
 * Arguments:
 * field - The custom field in question
 */
function get_custom_field_label($field, $domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);

    $sql = "SELECT cf_custom_label FROM " . TB_PREFIX . "custom_fields
            WHERE cf_custom_field = :field AND domain_id = :domain_id";
    $sth = dbQuery($sql, ':field', $field, ':domain_id', $domain_id);

    $cf = $sth->fetch();

    // grab the last character of the field variable
    $get_cf_number = $field[strlen($field) - 1];

    // if custom field is blank in db use the one from the LANG files
    if ($cf['cf_custom_label'] == NULL) {
        $cf['cf_custom_label'] = $LANG['custom_field'] . $get_cf_number;
    }

    return $cf['cf_custom_label'];
}

function calc_invoice_paid($inv_idField, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $x1 = "SELECT COALESCE(SUM(ac_amount), 0) AS amount
           FROM " . TB_PREFIX . "payment
           WHERE ac_inv_id = :inv_id AND domain_id = :domain_id";
    // @formatter:on
    $sth = dbQuery($x1, ':inv_id', $inv_idField, ':domain_id', $domain_id);
    if (!$result_x1Array = $sth->fetch()) return 0;

    $invoice_paid_Field = $result_x1Array['amount'];
    return $invoice_paid_Field;
}

function calc_customer_total($customer_id, $domain_id = '', $isReal = false) {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $real1 = '';
    $real2 = '';
    if ($isReal) {
        $real1 = " LEFT JOIN " . TB_PREFIX . "preferences pr
                          ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)";
        $real2 = " AND pr.status = 1";
    }

    $sql = "SELECT COALESCE(SUM(ii.total),  0) AS total
            FROM " . TB_PREFIX . "invoice_items ii
            INNER JOIN " . TB_PREFIX . "invoices iv ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
            $real1
            WHERE iv.customer_id  = :customer
              AND ii.domain_id    = :domain_id
            $real2";
    // @formatter:on
    $sth = dbQuery($sql, ':customer', $customer_id, ':domain_id', $domain_id);
    $invoice = $sth->fetch();

    return $invoice['total'];
}

function calc_customer_paid($customer_id, $domain_id = '', $isReal = false) {
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $real1 = '';
    $real2 = '';
    if ($isReal) {
        $real1 = " LEFT JOIN " . TB_PREFIX . "preferences pr
                          ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)";
        $real2 = " AND pr.status = 1";
    }

    $sql = "SELECT COALESCE(SUM(ap.ac_amount), 0) AS amount
            FROM " . TB_PREFIX . "payment ap
            INNER JOIN " . TB_PREFIX . "invoices iv ON (iv.id = ap.ac_inv_id AND iv.domain_id = ap.domain_id)
            $real1
            WHERE iv.customer_id = :customer
              AND ap.domain_id   = :domain_id
              $real2";
    // @formatter:on
    $sth = dbQuery($sql, ':customer', $customer_id, ':domain_id', $domain_id);
    $invoice = $sth->fetch();

    return $invoice['amount'];
}

/**
 * Function: calc_invoice_tax
 *
 * Calculates the total tax for a given invoices
 *
 * Arguments:
 * invoice_id - The name of the field, ie. Custom Field 1, etc..
 */
function calc_invoice_tax($invoice_id, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);

    // @formatter:off
    $sql = "SELECT SUM(tax_amount) AS total_tax
            FROM " . TB_PREFIX . "invoice_items
            WHERE invoice_id = :invoice_id AND domain_id = :domain_id";
    // @formatter:on
    $sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $domain_id);

    $tax = $sth->fetch();

    return $tax['total_tax'];
}

/**
 * Build screen values for displaying a custom field.
 * @param string $custom_field Name of the database field.
 * @param string $custom_field_value The value of this field.
 * @param string $permission Maintenance permission (read or write)
 * @param string $css_class_tr CSS class the the table row (tr)
 * @param string $css_class_th CSS class of the table heading (th)
 * @param string $css_class_td CSS class of the table detail (td)
 * @param string $td_col_span COLSPAN value to table detail row.
 * @param string $seperator Value to display between two values.
 * @return string Display/input string for a custom field. For "read" permission, the field to
 *         display the data. For "write" permission, the formatted label and field.
 */
function show_custom_field($custom_field, $custom_field_value, $permission, $css_class_tr, $css_class_th, $css_class_td,
                $td_col_span, $seperator) {
    $domain_id = domain_id::get();

    $write_mode = ($permission == 'write'); // if falst then in read mode.

    // Get the custom field number (last character of the name).
    $cfn = substr($custom_field, -1, 1);

    // Get custom field label
    $display_block = "";
    // @formatter:off
    $get_custom_label = "SELECT cf_custom_label
                         FROM " . TB_PREFIX . "custom_fields
                         WHERE cf_custom_field = :field AND domain_id = :domain_id";
    // @formatter:on
    $sth = dbQuery($get_custom_label, ':field', $custom_field, ':domain_id', $domain_id);

    $cf_label = '';
    $row = $sth->fetch();
    if (!empty($row['cf_custom_label'])) $cf_label = $row['cf_custom_label'];

    if (!empty($custom_field_value) || ($write_mode && !empty($cf_label))) {
        $custom_label_value = htmlsafe(get_custom_field_label($custom_field));
        // @formatter:off
        if ($write_mode) {
            $display_block =
<<<EOD
<tr>
    <th class="$css_class_th">$custom_label_value
        <a class="cluetip" href="#"
           rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
           title="Custom Fields"><img src="{$help_image_path}help-small.png" alt="" />
        </a>
    </th>
    <td><input type="text" name="customField$cfn" value="$custom_field_value" size="25" /></td>
</tr>
EOD;
        } else {
            $display_block =
<<<EOD
<tr class="$css_class_tr" >
    <th class="$css_class_th">$custom_label_value$seperator</th>
    <td class="$css_class_td" colspan="$td_col_span" >$custom_field_value</td>
</tr>
EOD;
        }
        // @formatter:on
    }
    return $display_block;
}
