<?php
require_once 'include/class/PdoDbException.php';
require_once 'include/class/OnClause.php';
require_once 'include/class/GroupBy.php';
require_once 'include/class/DbField.php';

class Join {
    const PREFIX = '/^si_/'; // Chg to use TB_PREFIX when only PHP 5.6x and up supported
    const TYPE = '/^(INNER|LEFT|RIGHT|FULL)$/';

    private $groupBy;
    private $onClause;
    private $table;
    private $tableAlias;
    private $type;

    /**
     * Make a JOIN statement
     * @param string $type Join type, <b>INNER</b>, <b>LEFT</b>, <b>RIGHT</b> or <b>FULL</b>.
     * @param string $table Database table to join. If not present, the database prefix will be added.
     * @param string $tableAlias Alias for table to use for column name references.
     * @throws PdoDbException if invalid values are passed.
     */
    public function __construct($type, $table, $tableAlias = null) {
        $this->type = strtoupper($type);
        if (preg_match(self::TYPE, $this->type) != 1) {
            throw new PdoDbException("Join() - Invalid type, $type, specified.");
        }

        if (is_a($table, "Select")) {
            $this->table = $table;
        } else {
            $this->table = self::addPrefix($table);
        }
        $this->tableAlias = $tableAlias;
        $this->groupBy = null;
        $this->onClause = null;
    }

    private function addPrefix($table) {
        if (preg_match(self::PREFIX, $table) != 1) return TB_PREFIX . $table;
        return $table;
    }

    /**
     * Add a simple item to the <b>OnClause</b>.
     * @param string $field Field (aka column) of table to be joined or available in the scope
     *        of fields from tables in the join statement.
     * @param mixed $value Value to use in the test. This can be a constant or a field in
     *        the table being joined to. Note that if this is a table field, the <i>DbField</i>
     *        class should be used to render it. Ex: obj->addSimpleItem(iv.id, new DbField(ii.id)).
     * @param string $connector Connector to the next item, <b>AND</b> or <b>OR</b>. If not
     *        specified, this is the last item in the <b>OnClause</b>.
     */
    public function addSimpleItem($field, $value, $connector = null) {
        if (!isset($this->onClause)) $this->onClause = new OnClause();
        $this->onClause->addSimpleItem($field, $value, $connector);
    }

    /**
     * Specify the <b>ON</b> clause to qualify join this table to the selection.
`    * @param OnClause $onClause Object of class type <b>OnClause</b>.
     */
    public function setOnClause(OnClause $onClause) {
        if (isset($this->onClause)) {
            throw new PdoDbException("Join setOnClause(): Attempt to set multiple \"OnClause\" statements.");
        }
        $this->onClause = $onClause;
    }

    /**
     * Add a <b>GROUP BY</b> object for this join.
     * @param GroupBy $groupBy
     */
    public function addGroupBy(GroupBy $groupBy) {
        $this->groupBy = $groupBy;
    }

    /**
     * Build the <b>JOIN<\b> statement from the specified components.
     * @param array $keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     *              Note that this array is initialized to empty by this method.
     * @return string <b>JOIN</b> statement.
     * @throws PdoDbException if unbalanced parenthesis have been specified.
     */
    public function build(&$keypairs) {
        $isSelect = is_a($this->table, "SELECT");
        $join = $this->type . " JOIN ";
        if ($isSelect) {
            $join .= "(" . $this->table->build($keypairs) . ") ";
        } else {
            $join .= "`" . $this->table . "` ";
        }
        $join .= "AS " . $this->tableAlias . " ";
        $join .= $this->onClause->build($keypairs);
        if (isset($this->groupBy)) {
            $join .= " " . $this->groupBy->build($keypairs);
        }
        return $join;
    }
}