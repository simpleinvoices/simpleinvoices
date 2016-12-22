<?php
require_once 'include/class/WhereClause.php';
require_once 'include/class/OnItem.php';

class OnClause extends WhereClause {

    /**
     * class constructor
     * @param WhereItem $whereItem If set, will add to this newly instantiated object.
     */
    public function __construct(OnItem $onItem = NULL) {
        parent::__construct($onItem);
    }

    /**
     * Add an <b>OnItem</b> object constructed from simple parameters.
     * @param string $field Field (aka column) of table to be joined or available in the scope
     *        of fields from tables in the join statement.
     * @param mixed $value Value to use in the test. This can be a constant or a field in
     *        the table being joined to. Note that if this is a table field, the <i>DbField</i>
     *        class should be used to render it. Ex: obj->addSimpleItem(iv.id, new DbField(ii.id)).
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     * @throws PdoDbException If an invalid operator or connector is found.
     */
    public function addSimpleItem($field, $value, $connector = null) {
        try {
            parent::addItem(new OnItem(false, $field, "=", $value, false, $connector));
        } catch (PdoDbException $pde) {
            throw new PdoDbException(preg_replace('/WhereClause/', 'OnClause', $pde->getMessage()));
        }
    }
    /**
     * Add a <b>OnItem</b> object to the <i>ON</i> clause.
     * @param OnItem $onItem
     * @throws PdoDbException If end of clause shows out of balance parenthesis.
     */
    public function addItem(WhereItem $whereItem) {
        try {
            parent::addItem($whereItem);
        } catch (PdoDbException $pde) {
            throw new PdoDbException(preg_replace('/WhereClause/', 'OnClause', $pde->getMessage()));
        }
    }


    /**
     * getter for $token_cnt.
     * Note that the current token count value has <b>NOT</b> been used to
     * make a unique token.
     * @return integer Current token count value.
     */
    public function getTokenCnt() {
        return parent::getTokenCnt();
    }

    /**
     * Build the <b>ON</b> clause to append to the request.
     * @param array $keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     * @throws PdoDbException if specified parenthesis have not been properly paired.
     */
    public function build(&$keyPairs) {
        try {
            $clause = preg_replace('/^WHERE /', '', parent::build($keyPairs));
        } catch (PdoDbException $pde) {
            throw new PdoDbException(preg_replace('/WhereClause/', 'OnClause', $pde->getMessage()));
        }
        return "ON ($clause)";
    }
}