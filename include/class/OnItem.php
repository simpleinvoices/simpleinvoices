<?php
require_once 'include/class/WhereItem.php';

class OnItem extends WhereItem {
    const OPERATORS = '/^(=|<>|<|>|<=|>=)$/';

    /**
     * Class constructor
     * @param boolean $open_paren Set to <b>true</b> if an opening parenthesis should be
     *        inserted before this term; otherwise set to <b>false</b>.
     * @param string $field Field (column) of the in the table being joined.
     * @param string $operator Valid SQL comparison operator to the <b>$field</b> record
     *        content test against the <b>$value</b> parameter. Currently only the relational
     *        operator are allowed: <b>=</b>, <b><></b>, <b><</b>, <b>></b>, <b><=</b> and <b>>=</b>.
     * @param mixed $value Value to use in the test. This can be a constant or a qualified field in
     *        the table being joined to.
     * @param boolean $close_paren Set to <b>true</b> if a closing parenthesis should be
     *        iinserted after this term; otherwise set to <b>false</b>.
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     * @throws PdoDbException If an invalid operator or connector is found.
     */
    public function __construct($open_paren, $field, $operator, $value, $close_paren, $connector = null) {
        $operator = strtoupper($operator);

        // This is a test for operators that is a subset of those in the WhereItem class.
        if (!preg_match(self::OPERATORS, $operator)) {
            throw new PdoDbException("OnItem - Invalid operator, $operator, specified. " .
                                              "See class documentation for allowed values.");
        }

        try {
            parent::__construct($open_paren, $field, $operator, $value, $close_paren, $connector);
        } catch (PdoDbException $pde) {
            throw new PdoDbException(preg_replace('/WhereItem/', 'OnItem', $pde->getMessage()));
        }
    }
}
