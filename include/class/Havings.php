<?php
require_once 'include/class/Having.php';

/**
 * Having class
 * @author Rich
 */
class Havings {
    private $havings;

    /**
     * Construct <b>Havings</b> object with initial <b>Having</b> element.
     * @param string $field
     * @param string $operator
     * @param mixed $value Can be a string, DbField object or an array.
     * @param string (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automaticvally to <b>AND</b> if a subsequent
     *        criterion is added.
     */
    public function __construct($field="", $operator="", $value="", $connector="") {
        if (empty($field) || empty($operator) || empty($value)) {
            $this->havings = array();
        } else {
            $having = new Having(false, $field, $operator, $value, $connector);
            $this->havings = array($having);
        }
    }

    /**
     * Add a <b>Having</b> object 
     * @param string $field
     * @param string $operator
     * @param mixed $value Can be a any data type needed by the specified <b>$operator</b>.
     * @param string (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automaticvally to <b>AND</b> if a subsequent
     *        criterion is added.
     */
    public function add($left_paren=false, $field, $operator, $value, $connector="", $right_paren=false) {
        $this->addDefaultConnector();
        $this->havings[] = new Having($left_paren, $field, $operator, $value, $connector, $right_paren);
    }

    /**
     * Add another <b>Having</b> object to this clause.
     * @param string $field
     * @param string $operator
     * @param mixed $value Can be a string, DbField object or an array.
     * @param string (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automaticvally to <b>AND</b> if a subsequent
     *        criterion is added.
     */
    public function addSimple($field, $operator, $value, $connector="") {
        $this->addDefaultConnector();
        $having = new Having(false, $field, $operator, $value, $connector, false);
        $this->havings[] = $having;
    }

    /**
     * Add <b>Havings</b> or <b>Having</b> object content to this object.
     * @param Havings|Having $havings Object with values to add.
     * @throws PdoDbException Invalid parameter type
     */
    public function addHavings($havings) {
        $this->addDefaultConnector();
        if (is_a($havings,"Having")) {
            $this->add($havings->getField(), $havings->getOperator(), $havings->getValue());
        } else if (is_a($havings, "Havings")) {
            $this->havings = array_merge($this->havings, $havings->getHavings());
        } else {
            error_log("Havings addHavings() - Invalid parameters type specified: " . print_r($havings,true));
            throw new PdoDbException("Havings addHavings() - Invalid parameters type. See error log for details.");
         }
    }

    /**
     * If the last <b>Having</b> object added does not have a connector, add
     * a default, <b>"AND"</b> connector.
     */
    private function addDefaultConnector() {
        $ndx = count($this->havings) - 1;
        if ($ndx >= 0) {
            $having = $this->havings[$ndx];
            if (empty($having->getConnector())) {
                $having->setConnector("AND");
                $this->havings[$ndx] = $having;
            }
        }
    }

    /**
     * Builds the formatted <b>HAVING</b> statment for collected <i>Having</i> objects.
     * @param array $keyPairs Associative array indexed by the PDO <i>token</i> that
     *        references the value of the token. Example: $keyParis[<b>':domain_id'</b>] with
     *        a value of <b>1</b>.
     * @return string Formatted <b>HAVING</b> clause component for collected criteria.
     */
    public function build(&$keyPairs) {
        if (empty($this->havings)) return "";
        $result = "HAVING";
        foreach($this->havings as $having) {
            $result .= " " . $having->build($keyPairs);
        }
        return $result;
    }

    /**
     * getter for class property.
     * @return Having[] Array of <b>Having</b> objects assigned to this this object.
     */
    protected function getHavings() {
        return $this->havings;
    }
}
