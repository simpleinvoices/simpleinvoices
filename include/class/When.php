<?php
class When {
    const OPERATORS = '/^(=|!=|<>|<|>|<=|>=)$/';

    private $field;
    private $operator;
    private $value;
    private $result;

   /**
    * Class constructor
    * @param string $field to place left of the operator.
    * @param string $operator Limited to <b>=</b>, <b>!=</b>, <b>&lt;=</b>, <b>&lt;&gt;</b>, <b>&lt;</b>,
    *        <b>&gt;</b> and <b>&gt;=</b>.
    * @param string $value Value or field to place on the right side of the operator.
    * @param string $result Value to assign if the test is true.
    * @throws PdoDbException If the operator is not on of those currently supported.
    */
    public function __construct($field, $operator, $value, $result) {
        // @formatter:off
        $this->field    = $field;
        $this->operator = $operator;
        $this->value    = $value;
        $this->result   = $result;
        // @formatter:on

        if (!preg_match(self::OPERATORS, $this->operator)) {
            throw new PdoDbException("When - Invalid operator, $this->operator, specified.");
        }
    }

    public function build() {
        $when = "WHEN " . $this->field . " " . $this->operator . " " .
                          $this->value . " THEN '" . $this->result . "' ";
        return $when;
    }
}
