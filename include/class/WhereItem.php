<?php

/**
 * Class for a single test item in the <b>WHERE</b> clause.
 * @author Rich Rowley
 */
class WhereItem {
    const CONNECTORS = '/AND|OR/';
    const OPERATORS = '/^(=|<>|<|>|<=|>=)$/';
    private $open_paren;
    private $field;
    private $operator;
    private $value;
    private $close_paren;
    private $connector;
    private $token;

    /**
     * Class constructor
     * @param boolean $open_paren Set to <b>true</b> if an opening parenthesis should be
     *        inserted before this term; otherwise set to <b>false</b>.
     * @param string $field The actual name of the field (column) in the table. This is
     *        a required parameter and <b>MUST</b> exist in the table.
     * @param string $operator Valid SQL comparison operator to the <b>$field</b> record
     *        content test against the <b>$value</b> parameter. Currently only the relational
     *        operator are allowed: <b>=</b>, <b><></b>, <b><</b>, <b>></b>, <b><=</b> and <b>>=</b>.
     * @param mixed $value Value to use in the test.
     * @param boolean $close_paren Set to <b>true</b> if a closing parenthesis should be
     *        iinserted after this term; otherwise set to <b>false</b>.
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     * @throws Exception If an invalid operator or connector is found.
     */
    public function __construct($open_paren, $field, $operator, $value, $close_paren, $connector = NULL) {
        $this->open_paren = $open_paren;
        $this->field = $field;
        $this->operator = strtoupper($operator);
        $this->value = $value;
        $this->close_paren = $close_paren;
        $this->connector = (isset($connector) ? strtoupper($connector) : '');
        $this->token = ':' . $field;

        if (!preg_match(self::OPERATORS, $this->operator)) {
            throw new Exception("WhereItem - Invalid operator, $this->operator, specified.");
        }

        if (!empty($this->connector) && !preg_match(self::CONNECTORS, $this->connector)) {
            throw new Exception("WhereItem - Invalid connector, $this->connector, specified.");
        }
    }

    /**
     * Builds the formatted selection criterion for this object.
     * @param array $keyPairs Associative array indexed by the PDO <i>token</i> that
     *        references the value of the token. Example: $keyParis[<b>':domain_id'</b>] with
     *        a value of <b>1</b>.
     * @return string Formatted <b>WHERE</b> clause component for this criterion.
     */
    public function build(&$keyPairs) {
        $item = '';
        if ($this->open_paren) $item .= '(';

        $item .= $this->field . ' ' . $this->operator . ' ' . $this->token . ' ';

        if ($this->close_paren) $item .= ') ';

        $item .= (empty($this->connector) ? ';' : $this->connector . ' ');

        $keyPairs[$this->token] = $this->value;

        return $item;
    }

    /**
     * Calculates unmatched parenthesis in this object.
     * @return integer Count of unmatched parenthesis in this object. A positive result
     *         is count of unmatched opening parenthesis, a negative result is count of
     *         unmatched closing parenthesis and a result of 0 means all parenthesis if
     *         any are matched.
     */
    public function parenCount() {
        $cnt = 0;
        if ($this->open_paren) $cnt++;
        if ($this->close_paren) $cnt--;
        return $cnt;
    }

    /**
     * Flags the end of items for the <b>WHERE</b> clause.
     */
    public function endOfClause() {
        return empty($this->connector);
    }
}
