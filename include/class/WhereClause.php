<?php
require_once 'include/class/PdoDbException.php';
require_once 'include/class/WhereItem.php';
/**
 * WhereClause class is a collection of <b>WhereItem</b> objects specifying the
 * selection criteria for PDO requests.
 * @author Rich
 */
class WhereClause {
    private $whereItems;
    private $paren_cnt;
    private $token_cnt;

    /**
     * class constructor
     * Instantiates an object of the <b>WhereClause</b> class.
     * @param WhereItem $whereItem (Optional) If set, will add to this newly instantiated object.
     */
    public function __construct(WhereItem $whereItem = NULL) {
        $this->clear();
        if (isset($whereItem)) $this->addItem($whereItem);
    }

    /**
     * Clear object contents
     */
    public function clear() {
        $this->whereItems = array();
        $this->paren_cnt = 0;
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
     * Add a <b>WhereItem</b> object to the <i>WHERE</i> clause.
     * @param WhereItem $whereItem
     * @throws PdoDbException If end of clause shows out of balance parenthesis.
     */
    public function addItem(WhereItem $whereItem) {
        $this->whereItems[] = $whereItem;
        $this->paren_cnt += $whereItem->parenCount();
        if ($whereItem->endOfClause() && $this->paren_cnt != 0) {
            // @formatter:off
            throw new PdoDbException("WhereClause - addItem(): Invalid clause termination. There are too " .
                            ($whereItem->parenCount() > 0 ? "few " : "many ") . "closing parenthesis.");
            // @formatter:on
        }
    }

    /**
     * Add a <b>WhereItem</b> that performs an equality check.
     * @param string $field Table column for the left side of the test. 
     * @param string $value Constant or <b>DbField</b> for the right side of the test.
     * @param string $connector (Optional) <b>AND</b> or <b>OR</b> connector if this
     *        is not that last statement in the <b>WHERE</b> clause.
     */
    public function addSimpleItem($field, $value, $connector=null) {
        $this->addItem(new WhereItem(false, $field, "=", $value, false, $connector));
    }

    /**
     * Class property getter
     * return $paren_cnt;
     */
    public function getParenCnt() {
        return $this->paren_cnt;
    }

    /**
     * Build the <b>WHERE</b> clause to append to the request.
     * @param array $keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     *              Note that if not set, this array is initialized to empty by this method.
     * @throws Exception if specified parenthesis have not been properly paired.
     */
    public function build(&$keyPairs) {
        if (!isset($keyPairs)) $keyPairs = array();
        if (empty($this->whereItems)) return '';

        if ($this->paren_cnt != 0) {
            throw new PdoDbException("WhereClause - build(): Parenthesis mismatch.");
        }

        $clause = "WHERE ";
        foreach ($this->whereItems as $whereItem) {
            if (!($whereItem instanceof WhereItem)) {
                // This can't happen unless the add logic validation is broken. The test is performed
                // to cause logic to treat the object as an instance of the class.
                throw new PdoDbException("WhereClause - build(): Invalid object type found in class array. " .
                                                       "Must be an instance of the WhereItem class.");
            }

            $clause .= $whereItem->build($this->token_cnt, $keyPairs);
        }

        return $clause;
    }
}
