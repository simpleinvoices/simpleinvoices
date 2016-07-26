<?php
require_once 'include/class/PdoDbException.php';

class CaseStmt {
    const OPERATORS = '/^(=|<>|<|>|<=|>=)$/';

    private $field;
    private $operator;
    private $value;
    private $then;
    private $else;
    private $as;

    public function __construct($field, $operator, $value, $then, $else, $as) {
        // @formatter:off
        $this->field    = $field;
        $this->operator = $operator;
        $this->value    = $value;
        $this->then     = $then;
        $this->else     = $else;
        $this->as       = $as;
        // @formatter:on

        if (!preg_match(self::OPERATORS, $this->operator)) {
            throw new PdoDbException("CaseStmt - Invalid operator, $this->operator, specified.");
        }
    }

    /**
     * Builds the formatted <b>CASE</b> for this object.
     * @return string Formatted <b>CASE</b> statement this criterion.
     */
    public function build() {
        $field = PdoDb::formatField($this->field);
        $item = "(CASE WHEN $field $this->operator '$this->value' " .
                      "THEN '$this->then' ELSE '$this->else' END) AS $this->as";
        return $item;
    }
}
