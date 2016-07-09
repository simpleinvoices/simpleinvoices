<?php
require_once 'include/class/WhereClause.php';
require_once 'include/class/OrderBy.php';
require_once 'include/class/DbInfo.php';

/**
 * PdoDb class
 * This class is designed to work with forms with fields named
 * using the name of the field in the <b>mysql</b> table being
 * processed.
 * <b>post</b> method.
 * @author Rich Rowley
 */
class PdoDb {
    private $constraints;
    private $debug;
    private $distinct;
    private $excludedFields;
    private $fauxPost;
    private $fieldPrefix;
    private $keyPairs;
    private $limit;
    private $orderBy;
    private $pdoDb;
    private $pdoDb2;
    private $selectList;
    private $table_schema;
    private $transaction;
    private $usePost;
    private $whereClause;

    /**
     * Establish the PDO connector to the database.
     * Note that the global values, <b>$host</b>, <b>$database</b>, <b>$admin</b> and <b>$password</b>
     * must be set prior to instantiating this class.
     * @param DbInfo $dbinfo Object with database access information.
     * @param boolean $debug Set to <b>true</b> to have the debug information
     *        written to the <i>error.log</i>.
     * @throws Exception if a database error occurs.
     */
    public function __construct(DbInfo $dbinfo, $debug=false) {
        $this->clearAll();

        $this->table_schema = $dbinfo->getDbname();
        $this->debug = $debug;
        $this->transaction = false;
        try {
            // @formatter:off
            // Used for user requests.
            $this->pdoDb = new PDO('mysql:host=' . $dbinfo->getHost() .
                                     '; dbname=' . $dbinfo->getDbname(),
                                                   $dbinfo->getAdmin(),
                                                   $dbinfo->getPassword());

            // Used internally to perform table structure lookups, etc. so these
            // queries will not impact inprocess activity for the user's requests.
            $this->pdoDb2 = new PDO('mysql:host=' . $dbinfo->getHost() .
                                      '; dbname=' . $dbinfo->getDbname(),
                                                    $dbinfo->getAdmin(),
                                                    $dbinfo->getPassword());
            // @formatter:on
        } catch (PDOException $e) {
            $str = "PdoDb - construct error: " . $e->getMessage();
            error_log($str);
            throw new Exception($str);
        }
    }

    /**
     * Class destructor
     * Verifies no incomplete transactions inprocess. If found, rollback performed.
     */
    public function __destruct() {
        if ($this->transaction) {
            error_log("PdoDb destruct - incomplete transaction - rollback performed.");
            $this->rollback();
        }
        $this->pdoDb = null;
        $this->pdoDb2 = null;
    }

    /**
     * Reset class properties with the exception of the database object,
     * to their default (unset) state.
     */
    public function clearAll($clearTran=true) {
        // @formatter:off
        $this->constraints    = null;
        $this->distinct       = false;
        $this->excludedFields = null;
        $this->fauxPost       = null;
        $this->fieldPrefix    = null;
        $this->keyPairs       = null;
        $this->limit          = 0;
        $this->orderBy        = null;
        $this->selectList     = null;
        $this->usePost        = true;
        $this->whereClause    = null;
        if ($clearTran && $this->transaction) {
            $this->rollback();
        }
        // @formatter:on
    }

    /**
     * Turn on debug mode.
     * Enables error log display of query requests.
     */
    public function debugOn() {
        $this->debug = true;
    }

    /**
     * Turn off debug mode.
     */
    public function debugOff() {
        $this->debug = false;
    }

    /**
     * Make a token name for the current <i>PdoDb</i> request.
     * @param string $token Name of the field
     * @param integer $cnt Counter used to make the unique token name.
     *        Note this parameter is <i>passed by reference</i> so it's updated
     *        value will be returned in it.
     * @return string Unique token name.
     */
    public static function makeToken($token, &$cnt) {
        return sprintf("%s_%03d", $token, $cnt++);
    }

    /**
     * getter for class property
     * @return string $table_scheme (aka database name)
     */
    public function getTableSchema() {
        return $this->table_schema;
    }

    /**
     * Set the <b>DISTINCT</b> attribute for selection
     * Note: If the request performed is not a <b>SELECT</b>, this
     * setting will be ignored.
     */
    public function setDistinct() {
        $this->distinct = true;
    }

    /**
     * Add a simple WhereItem testing for equality.
     * @param string $column Table column name.
     * @param string $value Value to test for.
     * @param string $connector (Optional) If specified, used to connect to a subsequent
     *        <i>WhereItem</i>. Typically "AND" or "OR".
     */
    public function addSimpleWhere($column, $value, $connector=null) {
        $this->addToWhere(new WhereItem(false, $column, "=", $value, false, $connector));
    }

    /**
     * Set the <b>WHERE</b> clause object to generate when the next request is performed.
     * @param Object $where Either an instance of <i>WhereItem</i> or <i>WhereClause</i>.
     *        Note: If a <i>WhereItem</i> is submitted, it will be added to the <i>WhereClause</i>.
     *        If a <i>WhereClause</i> is submitted, it will be set as the initial value replacing
     *        any previously set values.
     * @throws Exception if an invalid parameter type is submitted.
     */
    public function addToWhere($where) {
        if (is_a($where, "WhereItem")) {
            if (isset($this->whereClause)) {
                $this->whereClause->addItem($where);
            } else {
                $this->whereClause = new WhereClause($where);
            }
        } else if (is_a($where, "WhereClause")) {
            $this->whereClause = $where;
        } else {
            throw new Exception("PdoDb.php - addToWhere(): Item must be an object of WhereItem or WhereClause");
        }
    }

    /**
     * Set the <b>ORDER BY</b> statement object to generate when the next request is performed.
     * @param OrderBy $orderBy Can take several forms.
     *        1) A string that is the name of the field to order by in ascending order.
     *           Ex: "street_address".
     *        2) A two dimensional array that can contains the field name and the order to
     *           sort it by. Ex: array("street_address", "D").
     *        3) An array of arrays where each internal array has two dimensions with contents
     *           explained in #2 above.
     *        4) An OrderBy object that will replace any previous settings.
     * @throws Exception if an invalid parameter type is found.
     */
    public function setOrderBy($orderBy) {
        if (is_a($orderBy, "OrderBy")) {
            $this->orderBy = $orderBy;
        } else {
            if (!isset($this->orderBy)) $this->orderBy = new OrderBy();
            if (is_array($orderBy)) {
                foreach($orderBy as $item) {
                    if (is_array($item)) {
                        if (count($item) != 2) {
                            $str = "PdoDb setOrderby - field array is invalid. Must be <b>field</b> and <b>order</b>.";
                            error_log($str);
                            throw new Exception($str);
                        }
                        $this->orderBy->addField($item[0], $item[1]);
                    } else {
                        $this->orderBy->addField($item);
                    }
                }
            } else if (is_string($orderBy)) {
                $this->orderBy->addField($orderBy);
            } else {
                $str = "PdoDb setOrderBy(): Invalid parameter type. " . print_r($orderBy, true);
                error_log($str);
                throw new Exception($str);
            }
        }
    }

    /**
     * Set a limit on records accessed
     * @param integer $limit Value to specify in the <i>LIMIT</i> parameter.
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * Set the list of fields to be excluded from those included in the list of fields
     * specified in the request.
     * @param array $excludedFields Array of field names to exclude from the <i>$_POST</i> or if used,
     *        the <i>FAUX POST</i> array. These fields might be present in the <i>WHERE</i> clause
     *        but are to be excluded from the INSERT or UPDATE fields. Typically this is the unique
     *        identifier for the record but can be any field that would otherwie be included from
     *        the <i>$_POST</i> or <i>FAUX POST</i> file.
     * @throws Exception if the parameter is not an array.
     */
    public function setExcludedFields($excludedFields) {
        if (is_array($excludedFields)) {
            $this->excludedFields = $excludedFields;
        } else {
            $this->clearAll();
            $str = "PdoDb - setExcludedFields(): \"\$excludedFields\" parameter is not an array.";
            error_log($str);
            throw new Exception($str);
        }
    }

    /**
     * Prefix value to prepend to the <i>$_POST</i> or <i>FAUX POST</i> field names.
     * @param string $fieldPrefix Contains the prefix characters that are prepended to the
     *        field name from the database for reference on the screen. This allows a screen
     *        that modifies multiple tables to uniquely identify fields of the same name in
     *        each table. For example: The screen contains fields for two tables. Both
     *        table 1 and table 2 contain name and address fields (name, address, city,
     *        state, zip). To allow those fields for table 2 to be identified, a prefix
     *        of "t2" is used for table 2 fields. This means the "name" attribute for
     *        these fields will contain t2_name, t2_address, t2_city, t2_state and t2_zip.
     *        When a <i>PdoDb request</i> is submitted for table 1 fields, no prefix will
     *        be set. Then when the <i>PdoDb request</i> is submitted for talbe 2, this
     *        field prefix of <b>"t2"</b> will be set.
     */
    public function setFieldPrefix($fieldPrefix) {
        $this->fieldPrefix = $fieldPrefix;
    }

    /**
     * Set faux post mode and file.
     * @param array $fauxPost Array to use in place of the <b>$_POST</b> array.
     */
    public function setFauxPost($fauxPost) {
        $this->usePost = false;
        $this->fauxPost = $fauxPost;
    }

    /**
     * Specify the subset of fields that a <i>SELECT</i> is to access.
     * Note that default is to select all fields.
     * @param array $selectList List of field names to select.
     */
    public function setSelectList($selectList) {
        $this->selectList = $selectList;
    }

    /**
     * Output SQL string to error log if <b>debug</b> property set.
     * @param string $sql The sql query with parameter placeholders.
     *        Use <i>debugOn()</i> and <i>debugOff()</i> methods to
     *        toggle this option. The <b>$debug</b> parameter in the
     *        constructor  can be used to turn debug on when the object
     *        is instantiated.
     */
    private function debugger($sql) {
        if ($this->debug) {
            $keys = array();
            if ($this->keyPairs != null) {
                $values = ($this->keyPairs == null ? array() : $this->keyPairs);
                // build a regular expression for each parameter
                foreach ($this->keyPairs as $key => $value) {
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
                $sql = preg_replace($keys, $values, $sql, 1, $count);

                // Compact query to be logged
                $sql = preg_replace('/  +/', ' ', str_replace(PHP_EOL, '', $sql));
            }
            error_log("PdoDb - debugger: $sql");
        }
    }

    /**
     * Retrieves the record ID of the row just inserted.
     * @return Record ID
     * @throws Exception if database error occurs.
     */
    private function lastInsertId() {
        $sql = 'SELECT last_insert_id()';
        if ($sth = $this->pdoDb->prepare($sql)) {
            if ($sth->execute()) {
                $id = $sth->fetchColumn();
                return $id;
            }
        } else {
            $this->clearAll();
            error_log("PdoDb - lastInsertId(): Prepare error." . print_r($sth->errorInfo(), true));
            throw new Exception('PdoDb lastInsertId(): Prepare error.');
        }
    }

    /**
     * Get a list of fields (aka columns) in a specified table.
     * @param string $table_in Name of the table to get fields for.
     * @return array Column names from the table. An empty array is
     *         returned if no columns found.
     */
    private function getTableFields($table_in) {
        try {
        $table = $table_in;
        $columns = array();

        // @formatter:off
            $sql = "SELECT `column_name`
                      FROM `information_schema`.`columns`
                     WHERE `table_schema`=:table_schema
                       AND `table_name`  =:table;";
            $token_pairs = array(':table_schema'=>$this->table_schema,
                                 ':table'       =>$table);
            if ($sth = $this->pdoDb2->prepare($sql)) {
            if ($sth->execute($token_pairs)) {
                while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                        $nam = $row['column_name'];
                        $columns[$nam] = "";
                        $sql = "SELECT `constraint_name`
                                  FROM `information_schema`.`key_column_usage`
                                 WHERE `column_name` =:column_name
                                   AND `table_schema`=:table_schema
                                   AND `table_name`  =:table;";
                        $token_pairs = array(':column_name' =>$nam,
                                             ':table_schema'=>$this->table_schema,
                                             ':table'       =>$table);
                        if ($tth = $this->pdoDb2->prepare($sql)) {
                            if ($tth->execute($token_pairs)) {
                                while ($row2 = $tth->fetch(PDO::FETCH_ASSOC)) {
                                    if (!empty($columns[$nam])) $columns[$nam] .= ":";
                                    $columns[$nam] .= strtoupper($row2['constraint_name']);
                                }
                            }
                        }
                    } // end while
                }
            }
        } catch (Exception $e) {
            error_log("PdoDb getTableFields(): Error: " . $e->getMessage());
        }
        return $columns;
    }

    /**
     * Construct column, value and PDO parameter list for the specified request.
     * @param string $request "INSERT" or "UPDATE".
     * @param array $valuePairs Array of values keyed by the associated column.
     * @param integer $token_cnt Value used to make unique token names.
     * @return string columns and associated values formatted to append to the sql statement
     *         for the specified <b>$request</b>.
     */
    private function makeValueList($request, $valuePairs, &$token_cnt) {
        $sep = "";
        $colList = "";
        $valList = "";
        $col_token_list = "";
        $insert = ($request == "INSERT");
        $update = ($request == "UPDATE");
        $i = 0;
        foreach ($valuePairs as $column => $value) {
            $token = ":" . self::makeToken($column, $token_cnt);
            $this->keyPairs[$token] = $value;
            // Add value setting if token is not excluded from the list of values being inserted or updated,
            if (empty($this->excludedFields) || !array_key_exists($column, $this->excludedFields)) {
                if ($insert) {
                    $colList .= $sep . "`" . $column . "`";
                    $valList .= $sep . $token;
                } else if ($update) {
                    $col_token_list .= $sep . "`" . $column . "` = " . $token;
                }
                $sep = "," . (++$i % 5 == 0 ? "\n " : " ");
            }
        }

        $sql = "";
        if ($insert) {
            $sql = "($colList)\n VALUES ($valList)";
        } else if ($update) {
            $sql = $col_token_list;
        }
        return $sql;
    }

    /**
     * Begin a transaction.
     * @throw new Exception if a transaction is already in process.
     */
    public function begin() {
        if ($this->debug) error_log("begin()");
        if ($this->transaction) {
            $this->rollback();
            throw new Exception("PdoDb begin(): Called when transaction already in process.");
        }
        $this->clearAll();
        $this->transaction = true;
        $this->pdoDb->beginTransaction();
    }

    /**
     * Rollback actions performed as part of the current transaction.
     */
    public function rollback() {
        if ($this->transaction) {
            if ($this->debug) error_log("rollback()");
            $this->pdoDb->rollback();
            $this->transaction = false;
            $this->clearAll();
        } else {
            $this->clearAll();
            throw new Exception("PdoDb rollback(): Called when no transaction is in process.");
        }
    }

    /**
     * Commit actions performed in this transaction.
     * @throws Exception if called when no transaction is in process.
     */
    public function commit() {
        if ($this->transaction) {
            if ($this->debug) error_log("commit()");
            $this->pdoDb->commit();
            $this->transaction = false;
            $this->clearAll();
        } else {
            $this->clearAll();
            throw new Exception("PdoDb commit(): Called when no transaction is in process.");
        }
    }

    /**
     * Dynamically builds and executes a PDO request for a specified table.
     * @param string $request Type of request. Valid settings are: <b>SELECT</b>,
     *        <b>INSERT</b>, <b>UPDATE</b> and <b>DELETE</b>. Note that letter
     *        case of this parameter does not matter.
     * @param string $table Database table name.
     * @return mixed value based on <b>$request</b>.
     *         <b>SELECT</b> returns array of rows selected.
     *         <b>INSERT</b> returns the unique ID assigned to the inserted record if an
     *         auto increment field exisst, otherwise <b>true</b>.
     *         <b>UPDATE</b> and <b>DELETE</b> returns <b>true</b>.
     * @throws Exception if any unexpected setting or missing information is encountered.
     */
    public function request($request, $table) {
        $request = strtoupper($request);
        $pattern = '/^' . TB_PREFIX . '/';
        if ((preg_match($pattern, $table)) != 1) {
            $table = TB_PREFIX . $table;
        }

        if (!($columns = $this->getTableFields($table))) {
            $this->clearAll();
            throw new Exception("PdoDb - request(): Invalid table, $table, specified.");
        }

        $sql = "";
        $valuePairs = array();
        $this->keyPairs = array();

        // Build WHERE clause and get value pair list for tokens in the clause.
        if (empty($this->whereClause)) {
            $where = '';
            $token_cnt = 0;
        } else {
            $where = (empty($this->whereClause) ? '' : $this->whereClause->build($this->keyPairs));
            $token_cnt = $this->whereClause->getTokenCnt();
        }

        // Build ORDER BY statement
        $order = (empty($this->orderBy) ? '' : $this->orderBy->buildOrder());

        // Build LIMIT
        $limit = (empty($this->limit) ? '' : " LIMIT $this->limit");

        // Make an array of paired column name and values. The column name is the
        // index and the value is the content at that column.
        foreach ($columns as $column => $constraints) {
            // If present, prepend the prefix followed by an underscore to the column name
            // used to index the $_POST array.
            // @formatter:off
            $postColumn = (isset($this->fieldPrefix) ? $this->fieldPrefix . '_' . $column : $column);
            if (( $this->usePost && isset($_POST[$postColumn])) ||
                (!$this->usePost && isset($this->fauxPost[$postColumn]))) {
                $valuePairs[$column] = ($this->usePost ? $_POST[$postColumn] : $this->fauxPost[$postColumn]);
            }
            // @formatter:on
        }

        // @formatter:off
        $useValueList = true;
        switch ($request) {
            case "SELECT":
                $useValueList = false;
                if (isset($this->selectList)) {
                    $list = "";
                    foreach($this->selectList as $column) {
                        if (!empty($list)) $list .= ',';
                        $list .= '`' . $column . '`';
                    }
                } else {
                    $list = "*";
                }
                $sql  = "SELECT " . ($this->distinct ? "DISTINCT " : "") . $list . " FROM `" . $table . "` \n";
                break;

            case "INSERT":
                $sql  = "INSERT INTO `" . $table . "` \n";
                break;

            case "UPDATE":
                $sql  = "UPDATE `" . $table . "` SET \n";
                break;

            case "DELETE":
                $sql  = "DELETE FROM `" . $table . "` \n";
                break;

            default:
                error_log("PdoDb - request(): Request, $request, not implemented.");
                $this->clearAll();
                throw new Exception("PdoDb - request():  Request, $request, not implemented.");
        }

        if ($useValueList) $sql .= $this->makeValueList($request, $valuePairs, $token_cnt) . "\n";
        $sql .= (empty($where) ? "" : " " . $where) .
                (empty($order) ? "" : " " . $order) .
                (empty($limit) ? "" : " " . $limit);
        // @formatter:on
        return $this->query($sql, $this->keyPairs);
    }

    /**
     * Perform query for specified SQL statement and needed value pairs.
     * Note: This method provides the ability to perform an externally formatted
     * SQL statement with or without value pairs.
     * @param string $sql SQL statement to be performed.
     * @param array $valuePairs Array of value pairs. This parameter is optional
     *        and only needs to be specified if bind values are needed.
     *        Example: array(':id' => '7', ':domain_id' => '1');
     * @return Result varies with the request type. <b>INSERT</b> returns the
     *         auto increment unique ID (or blank if no such field), <b>SELECT</b>
     *         returns the associative array for selected rows, <b>SHOW</b> returns
     *         the numberic array of specified show request, otherwise <b>true</b>
     *         on success.
     * @throws Exception If unable to bind values or execute request.
     */
    public function query($sql, $valuePairs = null) {
        $this->debugger($sql);
        if (!($sth = $this->pdoDb->prepare($sql))) {
            error_log("PdoDb - query(): Prepare error." . print_r($sth->errorInfo(), true));
            $this->clearAll();
            throw new Exception('PdoDb query(): Prepare error.');
        }

        if (isset($valuePairs) && is_array($valuePairs)) {
            foreach ($valuePairs as $key => $val) {
                $pattern = "/[^a-zA-Z0-9_\-]" . $key . "[^a-zA-Z0-9_\-]|[^a-zA-Z0-9_\-]" . $key . "$/";
                $result = (preg_match($pattern, $sql));
                if (preg_match($pattern, $sql) == 1) {
                    if (!$sth->bindValue($key, $val)) {
                        error_log("PdoDb - query(): Unable to bind values" . print_r($sth->errorInfo(), true));
                        $this->clearAll();
                        throw new Exception('PdoDb - query(): Unable to bind values.');
                    }
                }
            }
        }

        if (!$sth->execute()) {
            $tmp = $this->debug;
            $this->debug = true;
            $this->debugger($sql);
            $this->debug = $tmp;
            error_log("PdoDb - query(): Execute error." . print_r($sth->errorInfo(), true));
            $this->clearAll();
            throw new Exception('PdoDb - query(): Execute error. See error_log.');
        }

        $parts = explode(' ', $sql);
        $request = strtoupper($parts[0]);
        if ($request == "INSERT") {
            if (empty($this->constraints) || preg_match('/:ID[:$]', $this->constraints)) {
            $result = $this->lastInsertId();
            } else {
                $result = "";
            }
        } else if ($request == "SELECT") {
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        } else if ($request == "SHOW") {
            $result = $sth->fetchAll(PDO::FETCH_NUM);
        } else {
            $result = true;
        }

        // Don't clear the transaction setting.
        $this->clearAll(false);
        return $result;
    }
}
