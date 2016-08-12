<?php
class Select {
    private $alias;
    private $list;
    private $fromStmt;
    private $whereClause;
    private $joins;
    private $token_cnt;

    /**
     * Class constructor
     * @param array $list One of several objects.
     *        <ol>
     *          <li><b>array()</b>: Ordered array of strings containing field names to include in the list.</li>
     *          <li><b>FunctionStmt</b> object</li>
     *          <li><b>CaseStmt</b> object</li>
     *        </ol>
     * @param FromStmt $fromStmt (Optional) Table being selected from.
     * @param WhereClause $whereClause (Optional) Constraints for the selection.
     * @param string $alias (Optional) Alias to assign to the select statement.
     * @throws PdoDbException if invalid parameter types are submitted. 
     */
    public function __construct($list, $fromStmt = null, $whereClause = null, $alias = null) {
        if (empty($list) || (!is_array($list) && !is_a($list, "FunctionStmt") && !is_a($list, "CaseStmt"))) {
            $str = "Select - __construct(): \$list parameter is not a non-empty, array or FunctionStmt object. ";
            error_log($str . print_r($list, true));
            throw new PdoDbException($str);
        }

        if (isset($fromStmt) && !is_a($fromStmt, "FromStmt")) {
            $str = "Select - __construct(): \$fromStmt parameter is not a non-empty, FromStmt object. ";
            error_log($str . print_r($fromStmt, true));
            throw new PdoDbException($str);
        }

        if (isset($whereClause) && !is_a($whereClause, "WhereClause")) {
            $str = "Select - __construct(): \$whereClause parameter is not a \"WhereClause\" object. ";
            error_log($str . print_r($whereClause, true));
            throw new PdoDbException($str);
        }

        $this->list = $list;
        $this->fromStmt = $fromStmt;
        $this->whereClause = $whereClause;
        $this->joins = null;
        $this->alias = $alias;
        $this->token_cnt = 0;
    }

    /**
     * getter for $token_cnt.
     * Note that the current token count value has <b>NOT</b> been used to
     * make a unique token.
     * @return integer Current token count value.
     */
    public function getTokenCnt() {
        return $this->token_cnt;
    }
    
    /**
     * Add a <b>JOIN</b> to this <b>SELECT</b> statement.
     * @param Join $join <b>JOIN</b> statement to include.
     */
    public function addJoin(Join $join) {
        if (!is_a($join, "JOIN")) {
            error_log("Select - addJoin(): Invalid parameter type. " . print_r($join, true));
            throw new PdoDbException("Select - addJoin(): Parameter is not a <b>JOIN</b> statement. See error log for details.");
        }
        if (empty($this->joins)) $this->joins = array();
        $this->joins[] = $join;
    }

    /**
     * Build select statement.
     * @return string <b>SELECT</b> statement created by specified values.
     */
    public function build(&$keyPairs) {
        $select = "(SELECT ";

        if (is_array($this->list)) {
            $first = true;
            foreach ($this->list as $item) {
                if ($first) $first = false;
                else $select .= ", ";
                if (is_object($item)) {
                    if (get_class($item) == "WhereClause") {
                        $select .= $item->build($keyPairs);
                        
                    } else {
                        $select .= $item->build();
                     }
                } else {
                    $select .= $item;
                }
            }
        } else {
            $select .= $this->list->build($keyPairs);
        }

        if (!empty($this->fromStmt)) $select .= " " . $this->fromStmt->build();

        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $select .= " " . $join->build($keyPairs);
            }
        }

        if (!empty($this->whereClause)) {
            $select .= " " . $this->whereClause->build($keyPairs);
            $this->token_cnt += $this->whereClause->getTokenCnt();
            
        }

        $select .= ")";
        if (!empty($this->alias)) $select .= " AS " . $this->alias;

        return $select;
    }
}