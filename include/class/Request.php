<?php
require_once 'ftcc/db/PdoDb.php';

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
     * @param string $field
     * @param string $value
     */
    public function addSimpleWhere($field, $value) {
        $this->addWhereItem(false, $field, "=", $value, false);
    }

    /**
     * addWhereItem
     * @param boolean $lft_paren
     * @param string $field
     * @param string $operator
     * @param string $value
     * @param boolean $rht_paren
     * @param string $connector
     */
    public function addWhereItem($lft_paren, $field, $operator, $value, $rht_paren, $connector=null) {
        if (empty($this->whereClause)) {
            $this->whereClause = new WhereClause(new WhereItem($lft_paren, $field, $operator, $value, $rht_paren, $connector));
        } else {
            $this->whereClause->addItem(new WhereItem($lft_paren, $field, $operator, $value, $rht_paren, $connector));
        }
    }

    /**
     * addOrderBy
     * @param unknown $field
     * @param string $order
     */
    public function addOrderBy($field, $order="A") {
        if (empty($this->orderBy)) {
            $this->orderBy= new OrderBy($field, $order);
        } else {
            $this->orderBy->addField($field, $order);
        }
    }

    /**
     * addSelectList
     * @param unknown $list
     */
    public function addSelectList($list) {
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

    public function addExcludedField($field) {
        $this->excludedFields[$field] = 1;
    }

    /**
     * Set the <i>LIMIT</i>.
     * @param integer $limit Maximum number of rows to retrieve.
     */
    public function setLimit($limit) {
        $this->limit = intval($limit);
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
     * @throws Exception if an error is thrown when the <b>request</b> is performed.
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
            throw new Exception("Request performRequest() - " . $e->getMessage());
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
