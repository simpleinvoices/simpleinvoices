<?php
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
     * @param WhereItem $whereItem If set, will add to this newly instantiated object.
     */
    public function __construct($whereItem = NULL) {
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
     * Add a <b>WhereItem</b> object to the <i>where</i> clause.
     * @param WhereItem $whereItem
     */
    public function addItem(WhereItem $whereItem) {
        $this->whereItems[] = $whereItem;
        $this->paren_cnt += $whereItem->parenCount();

        if ($whereItem->endOfClause() && $this->paren_cnt != 0) {
            // @formatter:off
            throw new Exception("WhereClause - addItem(): Invalid clause termination. There are too " .
                            ($whereItem->parenCount() > 0 ? "few " : "many ") . "closing parenthesis.");
            // @formatter:on
        }
    }

    /**
     * Build the where clause to append to the request.
     * @param array $keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     *              Note that this array is initialized to empty by this method.
     * @throws Exception if specified parenthesis have not been properly paired.
     */
    public function build(&$keyPairs) {
        $keyPairs = array();
        if (empty($this->whereItems)) return '';

        if ($this->paren_cnt != 0) {
            throw new Exception("WhereClause - build(): Parenthesis mismatch.");
        }

        $clause = "WHERE ";
        foreach ($this->whereItems as $whereItem) {
            if (!($whereItem instanceof WhereItem)) {
                // This can't happen unless the add logic validation is broken. The test is performed
                // to cause logic to treat the object as an instance of the class.
                throw new Exception("WhereClause - build(): Invalid object type found in class array. " .
                                "Must be an instance of the WhereItem class.");
            }

            $clause .= $whereItem->build($this->token_cnt, $keyPairs);
        }

        return $clause;
    }
}
