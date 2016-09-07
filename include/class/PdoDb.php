<?php
require_once 'include/class/PdoDbException.php';
require_once 'include/class/FunctionStmt.php';
require_once 'include/class/WhereClause.php';
require_once 'include/class/WhereItem.php';
require_once 'include/class/FromStmt.php';
require_once 'include/class/CaseStmt.php';
require_once 'include/class/OrderBy.php';
require_once 'include/class/Select.php';
require_once 'include/class/DbInfo.php';
require_once 'include/class/DbField.php';
require_once 'include/class/Join.php';

/**
 * PdoDb class
 * This class is designed to work with forms with fields named
 * using the name of the field in the <b>mysql</b> table being
 * processed.
 * <b>post</b> method.
 * @author Rich Rowley
 */
class PdoDb {
    const TBPREFIX_PATTERN = '/^' . TB_PREFIX . '/';
    
    private $caseStmts;
    private $constraints;
    private $debug;
    private $distinct;
    private $excludedFields;
    private $fauxPost;
    private $fieldPrefix;
    private $functions;
    private $groupBy;
    private $joinStmts;
    private $keyPairs;
    private $limit;
    private $orderBy;
    private $pdoDb;
    private $pdoDb2;
    private $selectAll;
    private $selectList;
    private $selectStmts;
    private $table_columns;
    private $table_constrants;
    private $table_engine;
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
     * @throws PdoDbException if a database error occurs.
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
                                                   $dbinfo->getUsername(),
                                                   $dbinfo->getPassword());

            // Used internally to perform table structure lookups, etc. so these
            // queries will not impact inprocess activity for the user's requests.
            $this->pdoDb2 = new PDO('mysql:host=' . $dbinfo->getHost() .
                                      '; dbname=' . $dbinfo->getDbname(),
                                                    $dbinfo->getUsername(),
                                                    $dbinfo->getPassword());
            // @formatter:on
        } catch (PDOException $e) {
            $str = "PdoDb - construct error: " . $e->getMessage();
            error_log($str);
            throw new PdoDbException($str);
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
        $this->caseStmts        = null;
        $this->constraints      = null;
        $this->distinct         = false;
        $this->excludedFields   = null;
        $this->fauxPost         = null;
        $this->fieldPrefix      = null;
        $this->functions        = null;
        $this->groupBy          = null;
        $this->joinStmts        = null;
        $this->keyPairs         = null;
        $this->limit            = 0;
        $this->orderBy          = null;
        $this->selectAll        = false;
        $this->selectList       = null;
        $this->selectStmts      = null;
        $this->table_columns    = null;
        $this->table_constrants = null;
        $this->usePost          = true;
        $this->whereClause      = null;
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
        $token = preg_replace('/\./', '_', $token);
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
     * Add and entry for a table column to correct.
     * @param string $column Name of table field.
     * @param string $type Data type of the table column, ex: VARCHAR(255)
     * @param string $attributes Additional column attributs, 
     *        ex: NOT NULL AUTO_INCREMENT
     */
    public function addTableColumns($column, $type, $attributes) {
        $structure = $type . " " . $attributes;
        if (empty($this->table_columns)) {
            $this->table_columns = array();
        }
        $this->table_columns[$column] = $structure;
    }

    /**
     * Specify the table engine
     * @param unknown $engine
     */
    public function addTableEngine($engine) {
        $this->table_engine = $engine;
    }

    /**
     * Specify <b>ALTER TABLE</b> contraints to add to table columns.
     * @param string $column Name of table field the contraint is being aplied to.
     * @param string $constrant Constrant to apply to the table field. Note that
     *        the <b>$column</b> will be appended to the end of the <b>$constrant</b>
     *        unless there is a <i>tilde</i>, <b>~</b>, character in it. If present,
     *        the <b>$column</b> will be added in place of the <i>tilde</i>.
     */
    public function addTableConstraints($column, $constrant) {
        if (empty($this->table_constrants)) $this->table_constrants = array();
        $this->table_constrants[$column] = $constrant;
    }

    /**
     * Set the <b>WHERE</b> clause object to generate when the next request is performed.
     * @param Object $where Either an instance of <i>WhereItem</i> or <i>WhereClause</i>.
     *        Note: If a <i>WhereItem</i> is submitted, it will be added to the <i>WhereClause</i>.
     *        If a <i>WhereClause</i> is submitted, it will be set as the initial value replacing
     *        any previously set values.
     * @throws PdoDbException if an invalid parameter type is submitted.
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
            throw new PdoDbException("PdoDb.php - addToWhere(): Item must be an object of WhereItem or WhereClause");
        }
    }

    /**
     * Specify functions with parameters to list of those to perform
     * @param string $function Function to include in parameter list. Example: count(id).
     */
    public function addToFunctions($function) {
        if (is_string($function) || is_a($function, "FunctionStmt")) {
            if (isset($this->functions)) {
                $this->functions[] = $function;
            } else {
                $this->functions = array($function);
            }
        } else {
            throw new PdoDbException("PdoDb - addToFunctions(): Parameter number be a string or a FunctionStmt.");
        }
    }

    /**
     * Specify <b>Select</b> object to add to the select list.
     * @param Select $selectStmt Object to include in parameter list.
     */
    public function addToSelectStmts(Select $selectStmt) {
        if (isset($this->selectStmts)) {
            $this->selectStmts[] = $selectStmt;
        } else {
            $this->selectStmts = array($selectStmt);
        }
    }

    /**
     * Add a <b>Join</b> object to this request.
     * @param Join $join Parameter can take several forms:
     *        <ol>
     *          <li><b>Join class object</b></li>
     *          <li><b>Array with 4 values</b>. The values are:
     *            <ol>
     *              <li><b>string</b>: Type of join.</li>
     *              <li><b>string</b>: Table to join.</li>
     *              <li><b>string</b>: Alias for table.</li>
     *              <li><b>OnClause</b>: Object with join information.</li>
     *            </ol>
     *          <li><b>Array with 5 values</b>. The values are:
     *            <ol>
     *              <li><b>string</b>: Type of join.</li>
     *              <li><b>string</b>: Table to join.</li>
     *              <li><b>string</b>: Alias for table.</li>
     *              <li><b>string</b>: Left field to join on.</li>
     *              <li><b>string</b>: Right field to join on.</li>
     *            </ol>
     *          </li>
     *        </ol>
     * @throws PdoDbException If invalid values are passed.
     */
    public function addToJoins($join) {
        if (empty($this->joinStmts)) $this->joinStmts = array();
        if (is_a($join, "Join")) {
            $this->joinStmts[] = $join;
            return;
        }

        if (!is_array($join)) {
            error_log("PdoDb - addToJoins(): parameter join - " . print_r($join,true));
            throw new PdoDbException("PdoDb - addToJoins(): Invalid parameter type specified. See error_log for details.");
        }

        // type, table, alias, OnClause
        // type, table, alias, field, value
        if (count($join) == 4) {
            $type = $join[0];
            $table = $join[1];
            $alias = $join[2];
            $onClause = $join[3];
            if (!is_string($type) || !is_string($table) || !is_string($alias) || !is_a($onClause, "OnClause")) {
                if (is_a($onClause, "OnClause")) {
                    throw new PdoDbException("PdoDb - addToJoins(): Array submitted. Non-string content where string required.");
                } else {
                    throw new PdoDbException("PdoDb - addToJoins(): Array submitted. Non-class (OnClause) data where class object required.");
                }
            }
            $jn = new Join($type, $table, $alias);
            $jn->setOnClause($onClause);
            $this->joinStmts[] = $jn;
        } else if (count($join) == 5) {
            $type = $join[0];
            $table = $join[1];
            $alias = $join[2];
            $field = $join[3];
            $value = $join[4];
            if (!is_string($type) || !is_string($table) || !is_string($alias) || !is_string($field) || !is_string($value)) {
                throw new PdoDbException("PdoDb - addToJoins(): Array submitted contains non-string fields.");
            }
            $jn = new Join($type, $table, $alias);
            $jn->addSimpleItem($field, $value);
            $this->joinStmts[] = $jn;
        } else {
            throw new PdoDbException("PdoDb - addToJoins(): Array submitted with invalid content.");
        }
    }

    /**
     * Add a <b>CaseStmt</b> object to this request.
     * @param CaseStmt $caseStmt Object to build a <b>CASE</b> statement from.
     */
    public function addToCaseStmts(CaseStmt $caseStmt) {
        if (isset($this->caseStmt)) {
            $this->caseStmts[] = $caseStmt;
        } else {
            $this->caseStmts = array($caseStmt);
        }
    }

    /**
     * Set the <b>ORDER BY</b> statement object to generate when the next request is performed.
     * Note that this method can be called multiple times to add additional values.
     * @param OrderBy $orderBy Can take several forms.
     *        1) A string that is the name of the field to order by in ascending order.
     *           Ex: "street_address".
     *        2) An array with two elements. The first is the field name and the second is the
     *           order to sort it by. Ex: array("street_address", "D").
     *        3) An array of arrays where each internal array has two elements as explained in #2 above.
     *        4) An OrderBy object that will replace any previous settings.
     * @throws PdoDbException if an invalid parameter type is found.
     */
    public function setOrderBy($orderBy) {
        if (is_a($orderBy, "OrderBy")) {
            $this->orderBy = $orderBy;
        } else {
            if (!isset($this->orderBy)) $this->orderBy = new OrderBy();
            if (is_array($orderBy)) {
                if (is_array($orderBy[0])) {
                    foreach($orderBy as $item) {
                        if (count($item) != 2) {
                            $str = "PdoDb setOrderby - field array is invalid. Must be <b>field</b> and <b>order</b>.";
                            error_log($str);
                            throw new PdoDbException($str);
                        }
                        $this->orderBy->addField($item[0], $item[1]);
                    }
                } else {
                    if (count($orderBy) != 2) {
                        $str = "PdoDb setOrderby - field array is invalid. Must be <b>field</b> and <b>order</b>.";
                        error_log($str);
                        throw new PdoDbException($str);
                    }
                    $this->orderBy->addField($orderBy[0], $orderBy[1]);
                }
            } else if (is_string($orderBy)) {
                $this->orderBy->addField($orderBy);
            } else {
                $str = "PdoDb setOrderBy(): Invalid parameter type. " . print_r($orderBy, true);
                error_log($str);
                throw new PdoDbException($str);
            }
        }
    }

    /**
     * Set the <b>GROUP BY</b> statement object to generate when the next request is performed.
     * Note that this method can be called multiple times to add additional values.
     * @param mixed $groupBy Can take one of two forms.
     *        1) A string that is the name of the field to group by.
     *           Ex: "street_address".
     *        2) An ordered array that contains a list of field names to group by. The list is
     *           high to low group by levels.
     * @throws PdoDbException if an invalid parameter type is found.
     */
    public function setGroupBy($groupBy) {
        if (!isset($this->groupBy)) $this->groupBy = array();
        if (is_array($groupBy)) {
            foreach($groupBy as $item) {
                if (!is_string($item)) {
                    $str = "PdoDb setGroupBy - <b>\$groupBy</b> parameter is not valid.";
                    error_log($str);
                    throw new PdoDbException($str);
                }
                $this->groupBy[] = $item;
            }
        } else if (is_string($groupBy)) {
            $this->groupBy[] = $groupBy;
        } else {
            $str = "PdoDb setGroupBy(): Invalid parameter type. " . print_r($groupBy, true);
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Set a limit on records accessed
     * @param integer $limit Value to specify in the <i>LIMIT</i> parameter.
     * @param integer $offset Number of records to skip before reading the next $limit amount.
     */
    public function setLimit($limit, $offset=0) {
        $this->limit = ($offset > 0 ? $offset . ", " : "") . $limit;
    }


    /**
     * Set the list of fields to be excluded from those included in the list of fields
     * specified in the request.
     * @param array $excludedFields An associative array keyed by the <b>column names</b> to exclude
     *        from the <i>$_POST</i> or if used, the <i>FAUX POST</i> array. These fields might be
     *        present in the <i>WHERE</i> clause but are to be excluded from the INSERT or UPDATE
     *        fields. Typically this is the unique identifier for the record but can be any field
     *        that would otherwie be included from the <i>$_POST</i> or <i>FAUX POST</i> file.
     * @throws PdoDbException if the parameter is not an array.
     */
    public function setExcludedFields($excludedFields) {
        if (is_array($excludedFields)) {
            $this->excludedFields = $excludedFields;
        } else {
            $this->clearAll();
            $str = "PdoDb - setExcludedFields(): \"\$excludedFields\" parameter is not an array.";
            error_log($str);
            throw new PdoDbException($str);
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
     * @param array $fauxPost Array to use in place of the <b>$_POST</b> superglobal.
     *        Use the <b>table column name</b> as the index and the value to set the
     *        column to as the value of the array at the column name index.
     *        Ex: $fauxPost['name'] = "New name"; 
     */
    public function setFauxPost($fauxPost) {
        $this->usePost = false;
        $this->fauxPost = $fauxPost;
    }

    /**
     * setter for class property.
     * @param boolean $selectAll
     */
    public function setSelectAll($selectAll) {
        $this->selectAll = $selectAll;
    }

    /**
     * Specify the subset of fields that a <i>SELECT</i> is to access.
     * Note that default is to select all fields.
     * @param mixed $selectList Can take one of two forms.
     *        1) A string with the field name to select from the table.
     *           Ex: "street_address".
     *        2) An array of field names to select from the table.
     *           Ex: array("name", "street_address", "city", "state", "zip").
     * @throws PdoDbException if an invalid parameter type is found.
     */
    public function setSelectList($selectList) {
        if (is_array($selectList)) {
            $this->selectList = $selectList;
        } else if (is_string($selectList)) {
            $this->selectList = array($selectList);
        } else {
            $str = "PdoDb setSelectList(): Invalid parameter type. " . print_r($selectList, true);
            error_log($str);
            throw new PdoDbException($str);
        }
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
                $count = null;
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
     * @throws PdoDbException if database error occurs.
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
            throw new PdoDbException('PdoDb lastInsertId(): Prepare error.');
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
                        $token_pairs = array(':column_name' => $nam,
                                             ':table_schema'=> $this->table_schema,
                                             ':table'       => $table
                        );
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
     * @throw new PdoDbException if a transaction is already in process.
     */
    public function begin() {
        if ($this->debug) error_log("begin()");
        if ($this->transaction) {
            $this->rollback();
            throw new PdoDbException("PdoDb begin(): Called when transaction already in process.");
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
            throw new PdoDbException("PdoDb rollback(): Called when no transaction is in process.");
        }
    }

    /**
     * Commit actions performed in this transaction.
     * @throws PdoDbException if called when no transaction is in process.
     */
    public function commit() {
        if ($this->transaction) {
            if ($this->debug) error_log("commit()");
            $this->pdoDb->commit();
            $this->transaction = false;
            $this->clearAll();
        } else {
            $this->clearAll();
            throw new PdoDbException("PdoDb commit(): Called when no transaction is in process.");
        }
    }

    /**
     * Enclose field in <b>back-ticks</b> and add table alias if
     * specified and not one already present.
     * @param string $field Field to modify.
     * @param string $alias Table alias or <b>null</b> if none.
     * @return string Updated field.
     */
    public static function formatField($field, $alias = null) {
        $matches = array();
        if (preg_match('/^([a-z]+)\.(.*)$/', $field, $matches)) {
            // Already an alias present
            $parts = array();
            if (preg_match('/(.*) +([aA][sS]) +(.*)/', $matches[2], $parts)) {
                // x.y AS z
                $field = '`' . $matches[1] . '`.' . ($parts[1] == '*' ? $parts[1] : '`' . $parts[1] . '`') . 
                         ' AS ' . $parts[3];
            } else {
                // x.y
                $field = '`' . $matches[1] . '`.' . ($matches[2] == '*' ? $matches[2] : '`' . $matches[2] . '`');
            }
        } else if (isset($alias)) {
            // Needs to have alias added.
            $field = '`' . $alias . '`.`' . $field . '`';
        } else {
            $field = '`' . $field . '`';
        }
        return $field;
    }

    /**
     * Add the <b>SimpleInvoices</b> database table prefix, <b>si_</b>, if not already present.
     * @param string $table Table name that a prefix will be prepended to.
     * @return string Updated table name.
     */
    public static function addTbPrefix($table) {
        if ((preg_match(self::TBPREFIX_PATTERN, $table)) != 1) {
            return TB_PREFIX . $table;
        }
        return $table;
    }

    /**
     * Dynamically builds and executes a PDO request for a specified table.
     * @param string $request Type of request. Valid settings are: <b>SELECT</b>,
     *        <b>INSERT</b>, <b>UPDATE</b> and <b>DELETE</b>. Note that letter
     *        case of this parameter does not matter.
     * @param string $table Database table name.
     * @param string $alias (Optional) Alias for table name. Note that the alias need
     *         be in the select list. If not present, it will be added to selected fields.
     * @return Result varies with the request type. <b>INSERT</b> returns the
     *         auto increment unique ID (or blank if no such field), <b>SELECT</b>
     *         returns the associative array for selected rows or an empty array if
     *         no rows are found, <b>SHOW</b> returns the numberic array of
     *         specified <b>SHOW</b> request, otherwise <b>true</b> on success of
     *         <b>false</b> on failure..
     * @throws PdoDbException if any unexpected setting or missing information is encountered.
     */
    public function request($request, $table, $alias = null) {
        $request = strtoupper($request);
        $table = self::addTbPrefix($table);

        $sql = "";
        $valuePairs = array();
        $this->keyPairs = array();
        $token_cnt = 0;

        if ($request != "DROP") {
            if ($request == "ALTER TABLE") {
                if (empty($this->table_constrants)) {
                    throw new PdoDbException("PdoDb - request():");
                }

                foreach ($this->table_constrants as $column => $constraint) {
                    if (preg_match('/compound/', $column)) {
                        $column = preg_replace('/compound *(\(.*\)).*$/', '\1', $column);
                    }

                    if (strstr($constraint, '~') === false) {
                        $constraint .= " " . $column;
                    } else {
                        $constraint = preg_replace('/~/', $column, $constraint);
                    }

                    $sql .= "ALTER TABLE `$table` $constraint;";
                }
            } else if ($request == "CREATE TABLE") {
                foreach ($this->table_columns as $column => $structure) {
                    if (empty($sql)) {
                        $sql = "(";
                    } else {
                        $sql .= ", ";
                    }
                    $sql .= $column . " " . $structure;
                }

                if (empty($this->table_engine)) {
                    $this->clearAll();
                    throw new PdoDbException("PdoDb - request(): No engine specified for CREATE TABLE ($table).");
                }

                if (empty($sql)) {
                    throw new PdoDbException("PdoDb - request(): No columns specified to CREATE TABLE ($table).");
                }

                $sql .= ") ENGINE = " . $this->table_engine . ";";
            } else {
                if (!($columns = $this->getTableFields($table))) {
                    $this->clearAll();
                    throw new PdoDbException("PdoDb - request(): Invalid table, $table, specified.");
                }
    
                // Build WHERE clause and get value pair list for tokens in the clause.
                $where = "";
                if (!empty($this->whereClause)) {
                    $where = $this->whereClause->build($this->keyPairs);
                    $token_cnt += $this->whereClause->getTokenCnt();
                }
        
                // Build ORDER BY statement
                $order = (empty($this->orderBy) ? '' : $this->orderBy->build());

                // Build GROUP BY
                $group = (empty($this->groupBy) ? "" : "GROUP BY " . implode(',', $this->groupBy));

                // Build LIMIT
                $limit = (empty($this->limit) ? '' : " LIMIT $this->limit");
        
                // Make an array of paired column name and values. The column name is the
                // index and the value is the content at that column.
                foreach ($columns as $column => $this->constraints) {
                    // Check to see if a field prefix was specified and that there is no
                    // table alias present. If so, prepend the prefix followed by an underscore.
                    // @formatter:off
                    $postColumn = $column;
                    if (( $this->usePost && isset($_POST[$postColumn])) ||
                        (!$this->usePost && isset($this->fauxPost[$postColumn]))) {
                        $valuePairs[$column] = ($this->usePost ? $_POST[$postColumn] : $this->fauxPost[$postColumn]);
                    }
                    // @formatter:on
                }
            }
        }

        // @formatter:off
        $useValueList = ($request != "ALTER TABLE"  &&
                         $request != "CREATE TABLE" &&
                         $request != "DROP");
        switch ($request) {
            case "ALTER TABLE":
                // $sql containts the complete command
                break;

            case "CREATE TABLE":
                $sql = "CREATE TABLE `$table` $sql";
                break;

            case "DROP":
                $sql = "DROP TABLE IF EXISTS `$table` $sql";
                break;

            case "SELECT":
                $useValueList = false;
                if (!$this->selectAll &&
                    (isset($this->selectList) || isset($this->functions) || isset($this->caseStmts))) {
                    $list = "";
                } else {
                    $list = "*";
                }

                if (isset($this->selectList)) {
                    foreach($this->selectList as $column) {
                        if (!empty($list)) $list .= ', ';
                        $list .= $this->formatField($column, $alias);
                    }
                }

                if (isset($this->selectStmts)) {
                    foreach($this->selectStmts as $selectStmt) {
                        if (!empty($list)) $list .= ", ";
                        $list .= $selectStmt->build($this->keyPairs);
                        $token_cnt += $selectStmt->getTokenCnt();
                    }
                }

                if (isset($this->functions)) {
                    foreach($this->functions as $function) {
                        if (is_a($function, "FunctionStmt")) {
                            $function = $function->build($this->keyPairs);
                        }
                        if (!empty($list)) $list .= ", ";
                        $list .= $function;
                    }
                }

                if (isset($this->caseStmts)) {
                    foreach($this->caseStmts as $caseStmt) {
                        if (!empty($list)) $list .= ", ";
                        $list .= $caseStmt->build($this->keyPairs);
                    }
                }

                $sql  = "SELECT " . ($this->distinct ? "DISTINCT " : "") . "$list FROM `$table` " .
                        (isset($alias) ? "`$alias` " : "") . "\n";

                if (isset($this->joinStmts)) {
                    foreach($this->joinStmts as $join) {
                        $sql .= $join->build($this->keyPairs) . "\n";
                    }
                }

                break;

            case "INSERT":
                $sql  = "INSERT INTO `$table` \n";
                break;

            case "UPDATE":
                $sql  = "UPDATE `$table` SET \n";
                break;

            case "DELETE":
                $sql  = "DELETE FROM `$table` \n";
                break;

            default:
                error_log("PdoDb - request(): Request, $request, not implemented.");
                $this->clearAll();
                throw new PdoDbException("PdoDb - request():  Request, $request, not implemented.");
        }

        if ($useValueList) $sql .= $this->makeValueList($request, $valuePairs, $token_cnt) . "\n";
        $sql .= (empty($where) ? "" : " " . $where . "\n") .
                (empty($group) ? "" : " " . $group . "\n") .
                (empty($order) ? "" : " " . $order . "\n") .
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
     *         returns the associative array for selected rows or an empty array if
     *         no rows are found, <b>SHOW</b> returns the numberic array of
     *         specified <b>SHOW</b> request, otherwise <b>true</b> on success.
     * @throws PdoDbException If unable to bind values or execute request.
     */
    public function query($sql, $valuePairs = null) {
        $this->debugger($sql);
        if (!($sth = $this->pdoDb->prepare($sql))) {
            error_log("PdoDb - query(): Prepare error." . print_r($sth->errorInfo(), true));
            $this->clearAll();
            throw new PdoDbException('PdoDb query(): Prepare error.');
        }

        if (isset($valuePairs) && is_array($valuePairs)) {
            foreach ($valuePairs as $key => $val) {
                $pattern = "/[^a-zA-Z0-9_\-]" . $key . "[^a-zA-Z0-9_\-]|[^a-zA-Z0-9_\-]" . $key . "$/";
                if (preg_match($pattern, $sql) == 1) {
                    if (!$sth->bindValue($key, $val)) {
                        $this->clearAll();
                        throw new PdoDbException('PdoDb - query(): Unable to bind values.');
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
            throw new PdoDbException('PdoDb - query(): Execute error. See error_log.');
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
