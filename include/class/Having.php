<?php

/**
 * Class for a single test item in the <b>HAVING</b> clause.
 * @author Rich Rowley
 */
class Having {
    const OPERATORS = '/^(=|<>|<|>|<=|>=|BETWEEN)$/';
    const CONNECTORS = '/^(OR|AND)$/';

    private $left_paren;
    private $field;
    private $operator;
    private $value;
    private $connector;
    private $right_paren;

    /**
     * Class constructor
     * @param boolean $left_paren (Optional) <b>true</b> if a left parenthesis should be placed before this
     *        statement; else <b>false</b> (default) if no left parenthesis is to be added.
     * @param string $field
     * @param string $operator
     * @param mixed $value Can be a string, number or an array as needed by the specified <b>$operator</b>.
     * @param string $connector (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automaticvally to <b>AND</b> if a subsequent
     *        criterion is added.
     * @param boolean $right_paren (Optional) <b>true</b> if a right parenthesis should be placed after the
     *        <i>$value</i> parameter; else <b>false</b> (default) if no right parenthesis is to be added.
     */
    public function __construct($left_paren=false, $field, $operator, $value, $right_paren=false, $connector="") {
        $this->left_paren = $left_paren;
        $this->right_paren = $right_paren;

        $this->field = "";
        if (!empty($field)) {
            if (is_string($field) || is_a($field, "DbField") || is_a($field, "FunctionStmt")) {
                $this->field = $field;
            }
        }

        if (empty($this->field)) {
            error_log("Having - _construct(): field parameter type is invalid. field - " . print_r($field,true));
            throw new PdoDbException("Having - Invalid field parameter. See error log for details.");
        }

        if (!preg_match(self::OPERATORS, $operator)) {
            error_log(print_r(debug_backtrace(),true));
            error_log("Having - Invalid. operator - " . print_r($operator,true));
            throw new PdoDbException("Having - operator is invalid.");
        }
        $this->operator = $operator;

        if (!isset($value)) {
            error_log("Having - value parameter is invalid. value - " . print_r($value,true));
            throw new PdoDbException("Having - value is invalid. See error log for details.");
        } else if ($operator == "BETWEEN" && !is_array($value)) {
            error_log("Having - value parameter must be an array for BETWEEN operator.");
            throw new PdoDbException("Having - value is invalid. See error log for details.");
        } else if ($operator != "BETWEEN" && !is_a($value, "DbField") && !is_string($value) && !is_integer($value)) {
            error_log("Having - value parameter must be a string or DbField object for specified operator.");
            throw new PdoDbException("Having - value is invalid. See error log for details.");
        }
        $this->value = $value;

        $this->setConnector($connector);
    }

    /**
     * Builds the formatted selection criterion for this object.
     * @param array $keyPairs Associative array indexed by the PDO <i>token</i> that
     *        references the value of the token. Example: $keyParis[<b>':domain_id'</b>] with
     *        a value of <b>1</b>.
     * @return string Formatted <b>HAVING</b> clause component for this criterion.
     */
    public function build(&$keyPairs) {
        $having = ($this->left_paren ? "(" : "");

        if (is_a($this->field, "FunctionStmt")) {
            $having .= $this->field->build($keyPairs);
        } else if (is_a($this->field, "DbField")) {
            $having .= $this->field->genParm();
        } else {
            $having .= $this->field;
        }

        $having .= " " . $this->operator . " ";

        if (is_array($this->value)) {
            $having .= (empty($this->value[0]) ? "''" : "'" . $this->value[0] . "'") . " AND " .
                       (empty($this->value[1]) ? "''" : "'" . $this->value[1] . "'");
        } else {
            $having .= (empty($this->value) ? "''" : "'" . $this->value . "'");
        }

        $having .= ($this->right_paren ? ")" : "");
        $having .= (empty($this->connector) ? "" : " " . $this->connector);

        return $having;
    }

    /**
     * getter for class property
     * @return string $field
     */
    public function getField() {
        return $this->field;
    }

    /**
     * getter for class property
     * @return string $operator
     */
    public function getOperator() {
        return $this->operator;
    }

    /**
     * getter for class property
     * @return 
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * getter for class property
     * @return string $connector
     */
    public function getConnector() {
        return $this->connector;
    }

    /**
     * Test if left paren specified.
     * @return boolean <b>true</b> if paren is required; <b>false</b> if not.
     */
    public function isLeftParen() {
        return $this->left_paren;
    }

    /**
     * Test if right paren specified.
     * @return boolean <b>true</b> if paren is required; <b>false</b> if not.
     */
    public function isRightParen() {
        return $this->right_paren;
    }

    /**
     * Set the connector to a specified value.
     * @param string $connector Valid connector, <b>OR</b>, or <b>AND</b>.
     */
    public function setConnector($connector) {
        $this->connector = $connector;
        if (!empty($connector)) {
            if (!preg_match(self::CONNECTORS, $connector)) {
                error_log(print_r(debug_backtrace(),true));
                error_log("Having setConnector() - Invalid connector - " . print_r($connector,true));
                throw new PdoDbException("Having setConnector() - Connector is invalid.");
            }
        }
    }
}
