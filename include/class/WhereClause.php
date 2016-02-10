<?php
require_once './include/class/WhereItem.php';
/**
 * WhereClause class collect of <b>WhereItem</b> objects specifying the
 * selection criteria for PDO requests.
 * @author Rich
 */
class WhereClause {
    private $whereItems;
    private $paren_cnt;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->whereItems = array();
        $this->paren_cnt = 0;
    }

    /**
     * Add a <b>WhereItem</b> object to the <i>where</i> clause.
     * @param WhereItem $whereItem 
     * @throws Exception If parameter is not an instance of the <b>WhereItem</b> class or
     *      the parameter has the <i>endOfClass</i> property set and parenthsis in clause
     *      are not matched.
     */
    public function addItem($whereItem) {
        if (!($whereItem instanceof WhereItem)) {
            throw new Exception(
                            "WhereClause - addItem(): Invalid parameter type for \$whereItem " .
                            "parameter. It much be an instance of the WhereItem class.");
        }

        $this->whereItems[] = $whereItem;
        $this->paren_cnt += $whereItem->parenCount();

        if ($whereItem->endOfClause() && $this->paren_cnt != 0) {
            throw new Exception(
                            "WhereClause - addItem(): Invalid clause termination. There are too " .
                            ($whereItem->parenCount() > 0 ? "few " : "many ") . "closing parenthesis.");
        }
    }

    /**
     * Build the where clause to append to the request.
     * @param array $keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     * @throws Exception if specified parenthesis have not been properly paired.
     */
    public function build(&$keyPairs) {
        if (empty($this->whereItems)) return '';

        if ($this->paren_cnt != 0) {
            error_log("WhereClause - build(): Parenthesis mismatch.");
        }

        $clause = "WHERE ";
        foreach ($this->whereItems as $whereItem) {
            if (!($whereItem instanceof WhereItem)) {
                // This can't happen unless the add logic validation is broken. The test is performed
                // to cause logic to treat the object as an instance of the class.
                throw new Exception(
                                "WhereClause - build(): Invalid object type found in class array. " .
                                "Must be an instance of the WhereItem class.");
            }

            $clause .= $whereItem->build($keyPairs);
        }
        return $clause;
    }
}
