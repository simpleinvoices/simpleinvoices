<?php

/**
 * Request class.
 * When multiple database requests need to be processed with a single
 * <i>transaction</i>, <b>Request</b> objects can be collected in an
 * array for processing.
 * @author Rich
 * Apr 16, 2016
 */
class Request {
    private $excludedFields;
    private $fauxPostList;
    private $limit;
    private $orderBy;
    private $request;
    private $selectList;
    private $table;
    private $whereClause;

    /**
     * Class constructor
     * @param string $request Valid values are "SELECT", "INSERT", "UPDATE", "DELETE";
     * @param string $table_name Name of database table to perform <b>$request</b> on.
     */
    public function __construct($request, $table) {
        // @formatter:off
        $this->request      = $request;
        $this->table        = $table;

        $this->limit        = 0;
        $this->excludedFields = array();
        $this->orderBy      = null;
        $this->selectList   = array();
        $this->fauxPostList = array();
        $this->whereClause  = null;
        // @formatter:on
    }

    /**
     * Add a simple <b>WhereItem</b> that test for equality.
     * @param string $field The actual name of the field (column) in the table. This is
     *        a required parameter and <b>MUST</b> exist in the table.
     * @param mixed $value Value to use in the test. Note for <b>BETWEEN</b> this will be:
     *        <b>array(beginval,endval)</b>.
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     */
    public function addSimpleWhere($field, $value, $connector = null) {
        $this->addWhereItem(false, $field, "=", $value, false, $connector);
    }

    /**
     * addWhereItem
     * @param boolean $open_paren Set to <b>true</b> if an opening parenthesis should be
     *        inserted before this term; otherwise set to <b>false</b>.
     * @param string $field The actual name of the field (column) in the table. This is
     *        a required parameter and <b>MUST</b> exist in the table.
     * @param string $operator Valid SQL comparison operator to the <b>$field</b> record
     *        content test against the <b>$value</b> parameter. Currently only the relational
     *        operator are allowed: <b>=</b>, <b><></b>, <b><</b>, <b>></b>, <b><=</b> and <b>>=</b>.
     * @param mixed $value Value to use in the test. Note for <b>BETWEEN</b> this will be: <b>array(beginval,endval)</b>.
     * @param boolean $close_paren Set to <b>true</b> if a closing parenthesis should be
     *        iinserted after this term; otherwise set to <b>false</b>.
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     */
    public function addWhereItem($open_paren, $field, $operator, $value, $close_paren, $connector=null) {
        if (empty($this->whereClause)) {
            $this->whereClause = new WhereClause(new WhereItem($open_paren, $field, $operator, $value, $close_paren, $connector));
        } else {
            $this->whereClause->addItem(new WhereItem($open_paren, $field, $operator, $value, $close_paren, $connector));
        }
    }

    /**
     * Add a field to order by and its sort attribute.
     * @param mixed $field Either an <i>array</i> or <i>string</i>.
     *        The following forms are valid:
     *          <i>string</i> - A <i>field name</i> to be added to the collection
     *                          of ordered items with the specified <b>$order</b>.
     *          <i>array</i>  - An array of <i>field names</i> or of <i>arrays</i>.<br/>
     *                          If an <i>array of field names</i>, each <i>field name</i> is added
     *                          to the list of ordered items with default order of <b>ASC</b>.<br/>.
     *                          If an <i>array of arrays</i>, each element array can have <i>one</i>
     *                          or <i>two</i> elements. Element arrays of <i>two</dimensions contains
     *                          a <i>field name</i> for the first index and a sort order value in the
     *                          second element. Valid sort order values are: <b>A</b>, <b>ASC</b>, <b>D</b>
     *                          or <b>DESC</b>. Element arrays of <i>one</i> dimension contains a
     *                          <i>field name</i> and will use the value specified in the <b>$order</b>
     *                          parameter field for sorting.
     * @param string $order Order <b>A</b> ascending, <b>D</b> descending. Defaults to <b>A</b>.
     * @throws Exception if either parameter does not contain the form and values spcified for them.
     */
    public function addOrderBy($field, $order="A") {
        if (empty($this->orderBy)) {
            $this->orderBy= new OrderBy($field, $order);
        } else {
            $this->orderBy->addField($field, $order);
        }
    }

    /**
     * Specify the subset of fields that a <i>SELECT</i> is to access.
     * Note that default is to select all fields.
     * @param mixed $selectList Can take one of two forms.
     *        1) A string with the field name to select from the table.
     *           Ex: "street_address".
     *        2) An array of field names to select from the table.
     *           Ex: array("name", "street_address", "city", "state", "zip").
     */
    public function addSelectList($selectList) {
        if (is_array($list)) {
            foreach ($list as $field) {
                if (!in_array($field, $this->selectList)) {
                    $this->selectList[] = $field;
                }
            }
        } else {
            if (!in_array($list, $this->selectList)) {
                $this->selectList[] = $list;
            }
        }
    }

    /**
     * Adds the list of item and values that will be processed by the request.
     * @param array $fauxPostList An <i>associative array</i> with the <i>field name</i> as the
     *              index and the <b>field value</b> as the content. Note that if the <b>request</b>
     *              is an <i>INSERT</i>, the <b>field value</b> is not used.
     */
    public function addFauxPostList($fauxPostList) {
        foreach($fauxPostList as $field => $value) {
            $this->fauxPostList[$field] = $value;
        }
    }

    /**
     * Set faux post mode and file.
     * @param array $fauxPost Array to use in place of the <b>$_POST</b> superglobal.
     *        Use the <b>table column name</b> as the index and the value to set the
     *        column to as the value of the array at the column name index.
     *        Ex: $fauxPost['name'] = "New name";
     */
    public function setFauxPost($fauxPost) {
        if (empty($this->addFauxPostList())) {
            $this->fauxPostList = $fauxPost;
        } else {
            $this->addFauxPostList($fauxPost);
        }
    }

    /**
     * Set the list of fields to be excluded from those included in the list of fields
     * specified in the request.
     * @param array $excludedFields An associative array keyed by the <b>column names</b> to exclude
     *        from the <i>$_POST</i> or if used, the <i>FAUX POST</i> array. These fields might be
     *        present in the <i>WHERE</i> clause but are to be excluded from the INSERT or UPDATE
     *        fields. Typically this is the unique identifier for the record but can be any field
     *        that would otherwie be included from the <i>$_POST</i> or <i>FAUX POST</i> file.
     */
    public function setExcludedFields($excludedFields) {
        $this->excludedFields = $excludedFields;
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
     * getter for class property
     * @return string $request
     */
    public function isAdd() {
        return $this->request == "INSERT";
    }

    /**
     * getter for class property
     * @return string $table Table processed by this request.
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Perform this request.
     * @param PdoDb $pdoDb
     * @return mixed Result of the request.
     * @throws PdoDbException if an error is thrown when the <b>request</b> is performed.
     */
    public function performRequest(PdoDb $pdoDb) {
        try {
            // @formatter:off
            if (!empty($this->fauxPostList)) $pdoDb->setFauxPost($this->fauxPostList);
            if ($this->limit > 0           ) $pdoDb->setLimit($this->limit);
            if (!empty($this->orderBy)     ) $pdoDb->setOrderBy($this->orderBy);
            if (!empty($this->selectList)  ) $pdoDb->setSelectList($this->selectList);
            if (!empty($this->whereClause) ) $pdoDb->addToWhere($this->whereClause);
            if (!empty($this->excludedFields)) $pdoDb->setExcludedFields($this->excludedFields);
            // @formatter:on
            return $pdoDb->request($this->request, $this->table);
        } catch (Exception $e) {
            throw new PdoDbException("Request performRequest() - " . $e->getMessage());
        }
    }

    /**
     * describe
     * @return_string Description of the request
     */
    public function describe() {
        $msg = "$this->request for $this->table";
    }
}
