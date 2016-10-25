<?php
require_once "include/class/PdoDb.php";
global $auth_session, $config, $dbInfo;

/**
 *
 * @deprecated - Migrate to PdoDb class
 *               Rich Rowley 20160702
 */
$dbh = db_connector();

// @formatter:off
$pdoDb = new PdoDb($dbInfo);
$pdoDb->clearAll(); // to eliminate never used warning.

// For use by admin functions only. This avoids issues of
// concurrent use with user app object, <i>$pdoDb</i>.
$pdoDb_admin = new PdoDb($dbInfo);
$pdoDb_admin->clearAll();
// @formatter:on

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
    global $config, $databaseBuilt, $dbInfo;

    if (!$databaseBuilt) return NULL;

    if (!defined('PDO::MYSQL_ATTR_INIT_COMMAND') && $dbInfo->getAdapter() == "mysql" && $config->database->adapter->utf8 == true) {
        simpleInvoicesError("PDO::mysql_attr");
    }

    try {
        // @formatter:off
        $connlink = new PDO($dbInfo->getAdapter() . ':host=' . $dbInfo->getHost() .
                                                   '; port=' . $dbInfo->getPort() .
                                                 '; dbname=' . $dbInfo->getDbname(),
                                                               $dbInfo->getUsername(),
                                                               $dbInfo->getPassword());
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
    $query = preg_replace($keys, $values, $query, 1);
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
 */
function dbLogger($sqlQuery) {
    global $auth_session, $can_log, $pdoDb_admin;

    // Compact query to be logged
    $sqlQuery = preg_replace('/  +/', ' ', str_replace(PHP_EOL, '', $sqlQuery));
    if ($can_log && (preg_match('/^\s*select/iD', $sqlQuery) == 0) &&
                    (preg_match('/^\s*show\s*tables\s*like/iD', $sqlQuery) == 0)) {
        // Only log queries that could result in data/database modification
        $last = NULL;
        if (preg_match('/^(insert|update|delete)/iD', $sqlQuery)) $last = lastInsertId();

        // @formatter:off
        $pdoDb_admin->setFauxPost(array("domain_id" => $auth_session->domain_id,
                                        "timestamp" => CURRENT_TIMESTAMP,
                                        "userid"    => $auth_session->id,
                                        "sqlquerie" => trim($sqlQuery),
                                        "last_id"   => $last));
        $pdoDb_admin->request("INSERT", "log");
    }
}

/**
 * Retrieves the record ID of the most recently inserted row for the session.
 * Note: That the session is for the $dbh whose id was created by AUTO_INCREMENT
 * (MySQL) or a sequence (PostgreSQL). This is a convenience function to handle
 * the backend-specific details so you don't have to.
 * @return Record ID
 */
function lastInsertId() {
    global $config, $dbh, $dbInfo;
    $sql = 'SELECT last_insert_id()';
    $sth = $dbh->prepare($sql);
    $sth->execute();
    return $sth->fetchColumn();
}

/**
 * Load SI Extention information into $config->extension.
 */
function loadSiExtentions(&$ext_names) {
    global $config, $databaseBuilt, $patchCount, $pdoDb_admin;

    if ($databaseBuilt && $patchCount > "196") {
        $pdoDb_admin->addSimpleWhere("domain_id", domain_id::get(), "OR");
        $pdoDb_admin->addSimpleWhere("domain_id", 0);
        $pdoDb_admin->setOrderBy("domain_id");
        $rows = $pdoDb_admin->request("SELECT", "extensions");
        $extensions = array();
        foreach ($rows as $extension) {
            $extensions[$extension['name']] = $extension;
        }
        $config->extension = $extensions;
    }

    // If no extension loaded, load Core
    if (!$config->extension) {
        // @formatter:off
        $extension_core = new Zend_Config(
            array('core' => array('id'         => 1,
                                  'domain_id'  => 1,
                                  'name'       => 'core',
                                  'description'=> 'Core part of SimpleInvoices - always enabled',
                                  'enabled'    => 1)));
        $config->extension = $extension_core;
        // @formatter:on
    }

    // Populate the array of enabled extensions.
    $ext_names = array();
    foreach ($config->extension as $extension) {
        if ($extension->enabled == "1") {
            $ext_names[] = $extension->name;
        }
    }
}

/**
 * Get all patches.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 */
function getSQLPatches() {
    global $pdoDb_admin;
    $pdoDb_admin->addToWhere(new WhereItem(false, "sql_patch"    , "<>", "", false, "OR"));
    $pdoDb_admin->addToWhere(new WhereItem(false, "sql_release"  , "<>", "", false, "OR"));
    $pdoDb_admin->addToWhere(new WhereItem(false, "sql_statement", "<>", "", false));
    $pdoDb_admin->setOrderBy(array(array("sql_release","A"), array("sql_patch_ref","A")));

    $rows = $pdoDb_admin->request("SELECT", "sql_patchmanager");
    return $rows;
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
    $customFields = array();
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
 * Get a specific si_system_defaults record.
 * @param string $name Name for record to retrieve.
 * @param string $bool If true (default), a boolean field is being retrieved.
 *        If false, a character field is being retrieved.
 *        Note: If true, the result will be the $LANG word for 'enabled' or 'disabled'.
 * @param string $domain_id Domain user is logged into.
 * @return mixed Value from database or for bool, 'enabled' or 'disabled' word.
 */
function getDefaultGeneric($name, $bool = true, $domain_id = '') {
    global $LANG, $pdoDb, $databaseBuilt;

    // Make the value to return on a false or no DB build condition.
    $failed = ($bool ? $LANG['disabled'] : 0);
    if (!$databaseBuilt) return $failed;

    $pdoDb->addSimpleWhere("s.name", $name, "AND");
    $pdoDb->addSimpleWhere("s.domain_id", domain_id::get());
    $pdoDb->setSelectList("value");
    $rows = $pdoDb->request("SELECT", "system_defaults", "s");
    if (empty($rows)) return $failed;

    $row = $rows[0];
    $nameval = ($bool ? ($row['value'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']) : $row['value']);
    return $nameval;
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
 * @return boolean <b>true</b> "1" or "0"
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
    } elseif ($extension_info['enabled'] != ENABLED) {
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
    } else {
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
        $nam = $default['name'];
        $lcl_defaults["$nam"] = $default['value'];
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
            $nam = $default['name'];
            $lcl_defaults["$nam"] = $default['value']; // if setting is redefined, overwrite the previous value
        }
    }

    return $lcl_defaults;
}

function updateDefault($name, $value, $extension_name = "core") {
    $domain_id = domain_id::get();

    $extension_id = getExtensionID($extension_name);
    if (!($extension_id >= 0)) {
        die(htmlsafe("Invalid extension name: " . $extension_name));
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

/**
 * Ensure that there is a time value in the datetime object.
 *
 * @param string $in_date Datetime string in the format, "YYYY/MM/DD HH:MM:SS".
 *        Note: If time part is "00:00:00" it will be set to the current time.
 * @return string Datetime string with time set.
 */
function sqlDateWithTime($in_date) {
    $parts = explode(' ', $in_date);
    $date  = (isset($parts[0]) ? $parts[0] : "");
    $time  = (isset($parts[1]) ? $parts[1] : "00:00:00");
    if (!$time || $time == '00:00:00') {
        $time = date('H:i:s');
    }
    $out_date = "$date $time";
    return $out_date;
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
            $sth->fetch();
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
 * Test for database table existing.
 * @param string $table Table to check for.
 * @return true if specified table exists, false otherwise.
 */
function checkTableExists($table) {
    $sql = "SHOW TABLES LIKE '" . $table . "'";
    $sth = dbQuery($sql);
    if ($sth !== false && $sth->fetchAll()) {
        return true;
    }
    return false;
}

function checkFieldExists($table, $field) {
    global $dbh;
    // @formatter:off
    $sql = "SELECT 1 FROM information_schema.columns
            WHERE column_name = :field AND table_name = :table LIMIT 1";
    // @formatter:on

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

    $pattern = '/^' . TB_PREFIX . '/';
    if (!preg_match($pattern, $table_in)) {
        $table = TB_PREFIX . $table_in;
    } else {
        $table = $table_in;
    }

    $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = :table";

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

/**
 * Runs the HTML->PDF conversion with default settings
 * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
 * use absolute links (like http://my.host/image.gif).
 * @param $path_to_html String path to source html file.
 * @param $path_to_pdf String path to file to save generated PDF to.
 * @param boolean $download <b>true</b> sets <i>DestinationDownload</i> for the output destination.
 *        <b>false</b> sets <i>DestinationFile</i> for the output destination.
 */
function pdfThis($html_to_pdf, $pdfname, $download) {
     // set_include_path("../../../../library/pdf/");
    require_once ('./library/pdf/config.inc.php');
    require_once ('./library/pdf/pipeline.factory.class.php');
    require_once ('./library/pdf/pipeline.class.php');

    parse_config_file('./library/pdf/html2ps.config');

    require_once ("./include/init.php"); // for getInvoice() and getPreference()

    if (!function_exists('convert_to_pdf')) {
        function convert_to_pdf($html_to_pdf, $pdfname, $download) {
            global $config;

            $destination = $download ? "DestinationDownload" : "DestinationFile";

            $pipeline = PipelineFactory::create_default_pipeline("", ""); // Attempt to auto-detect encoding
            $pipeline->fetchers[] = new MyFetcherLocalFile($html_to_pdf); // Override HTML source

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
            if ($g_pt_scale) {}; // to eliminate unused variable warning

            $pipeline->configure($g_config);
            $pipeline->data_filters[] = new DataFilterUTF8("");
            $pipeline->destination = new $destination($pdfname);
            $pipeline->process($baseurl, $media);
        }
    }

    convert_to_pdf($html_to_pdf, $pdfname, $download);
}


function getNumberOfDonePatches() {
    global $pdoDb_admin;
    $pdoDb_admin->addToFunctions(new FunctionStmt("MAX", "sql_patch_ref", "count"));
    $rows = $pdoDb_admin->request("SELECT", "sql_patchmanager");
    // Returns number of patches applied
    return $rows[0]['count'];
}

function getNumberOfPatches() {
    global $si_patches;
    $patches = getNumberOfDonePatches();
    $patch_count = max(array_keys($si_patches));
    return $patch_count - $patches;
}

function runPatches() {
    global $si_patch;
    global $dbh;

    $sql = "SHOW TABLES LIKE '" . TB_PREFIX . "sql_patchmanager'";
    $sth = dbQuery($sql);
    $rows = $sth->fetchAll();

    $smarty_datas = array();

    if (count($rows) == 1) {
        $dbh->beginTransaction();

        for ($i = 0; $i < count($si_patch); $i++) {
            $smarty_datas['rows'][$i] = run_sql_patch($i, $si_patch[$i]);
        }

        $dbh->commit();

        $smarty_datas['message'] = "The database patches have now been applied. You can now start working with SimpleInvoices";
        $smarty_datas['html'] = "<div class='si_toolbar si_toolbar_form'><a href='index.php'>HOME</a></div>";
        $smarty_datas['refresh'] = 5;
    } else {
        $smarty_datas['html'] = "Step 1 - This is the first time Database Updates has been run";
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
    $smarty_datas = array();
    $smarty_datas['message'] = "The database patches are up to date. You can continue working with SimpleInvoices";
    $smarty_datas['html'] = "<div class='si_toolbar si_toolbar_form'><a href='index.php'>HOME</a></div>";
    $smarty_datas['refresh'] = 3;
    global $smarty;
    $smarty->assign("page", $smarty_datas);
}

function listPatches() {
    global $si_patch;

    $smarty_datas = array();
    $smarty_datas['message'] = "Your version of SimpleInvoices can now be upgraded. With this new release there are database patches that need to be applied";
    $smarty_datas['html'] = <<<EOD
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

    for ($p = 0; $p < count($si_patch); $p++) {
        $patch_name = htmlsafe($si_patch[$p]['name']);
        $patch_date = htmlsafe($si_patch[$p]['date']);
        if (check_sql_patch($p, $si_patch[$p]['name'])) {
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
        dbQuery($sql, ':description', $res['description'],
                      ':total'      , $res['gross_total']);
        $id = lastInsertId();

        $sql = "UPDATE  " . TB_PREFIX . "invoice_items
                SET product_id = :id,
                    unit_price = :price
                WHERE " . TB_PREFIX . "invoice_items.id = :item";
        dbQuery($sql, ':id', $id[0], ':price', $res['gross_total'], ':item', $res['id']);
        // @formatter:on
    }
}
 