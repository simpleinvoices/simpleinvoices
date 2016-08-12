<?php

/**
 * OrderBy class
 * @author Rich
 */
class OrderBy {
    private $orderByFields;

    /**
     * Class constructor.
     * @param string $field Primary field to order data by.
     * @param string $order Order <b>A</b> ascending, <b>D</b> descending.
     *        Defaults to ascending if not specified.
     * @throws Exception object if an invalid value is specified for the
     *         <b>order</b> parameter.
     */
    public function __construct($field = null, $order = 'A') {
        $this->orderByFields = array();
        if (isset($field)) $this->addField($field, $order);
    }

    /**
     * Add a field and order attribute.
     * @param mixed $field Either an <i>array</i> or <i>string</i>.
     *        The following forms are valid:
     *          <i>string</i> - A <i>field name</i> to be added to the collection
     *                          of ordered items with the specified <b>$order</b>.
     *          <i>array</i>  - An array of <i>field names</i> or of <i>arrays</i>.<br/>
     *                          If an <i>array of field names</i>, each <i>field name</i> is added
     *                          to the list of ordered items with default order of <b>ASC</b>.<br/>.
     *                          If an <i>array of arrays</i>, each element array can have <i>one</i>
     *                          or <i>two</i> elements. Element arrays of <i>two</dimensions contains
     *                          a <i>field name</i> for the first index and a sort order value in the
     *                          second element. Valid sort order values are: <b>A</b>, <b>ASC</b>, <b>D</b>
     *                          or <b>DESC</b>. Element arrays of <i>one</i> dimension contains a
     *                          <i>field name</i> and will use the value specified in the <b>$order</b>
     *                          parameter field for sorting.
     * @param string $order Order <b>A</b> ascending, <b>D</b> descending. Defaults to <b>A</b>.
     * @throws Exception if either parameter does not contain the form and values spcified for them.
     */
    public function addField($field, $order = 'A') {
        $lcl_order = strtoupper($order);
        if (preg_match('/^(A|D|ASC|DESC)$/', $lcl_order) != 1) {
            $str = "OrderBy - addField(): Invalid order, $lcl_order, specified.";
            error_log($str);
            throw new PdoDbException(str);
        }

        $lcl_order = ($lcl_order == 'A' ? 'ASC' : 'DESC');

        if (is_array($field)) {
            foreach($field as $item) {
                if (is_array($item)) {
                    if (count($item) == 2 && is_string($item[0]) &&
                        ($item[1] == 'A' || $item[1] == 'D')) {
                        $this->orderByFields[] = array($item[0], ($item[1] == 'A' ? 'ASC' : 'DESC'));
                    } else if (count($item) == 1 && is_string($item[0])) {
                        $this->orderByFields[] = array($item[0], $lcl_order);
                    } else {
                        $str  = "OrderBy - addFIeld(): Invalid array content. ";
                        $str .= (count($item) == 2 ? "field name: $item[0], order: $item[1]" :
                                 count($item) == 1 ? "field name: $item[0]" :
                                                     "Too many elements. Dimensions: " . count($item));
                        error_log($str);
                        throw new PdoDbException($str);
                    }
                } else {
                    $this->orderByFields[] = array($fields, $lcl_order);
                }
            }
        } else if (is_string($field)) {
            $item = array($field, $lcl_order);
            $this->orderByFields[] = $item;
        } else {
            $str = "OrderBy - addField(): Invalid <b>\$field</b> type. Field value is $field.";
        }
    }

    /**
     * Build the <b>ORDER BY</b> statement.
     * @param array $keypairs (Optional) Parameter exists for function call compatibility
     *        with other <i>PdoDb</i> class SQL build objects. 
     * @return Formatted <b>ORDER by</b> string.
     */
    public function build($keypairs = null) {
        $orderBy = '';
        foreach ($this->orderByFields as $items) {
            if (empty($orderBy)) {
                $orderBy = "ORDER BY ";
            } else {
                $orderBy .= ', ';
            }
            $orderBy .= PdoDb::formatField($items[0]) . ' ' . $items[1];
        }
        return $orderBy;
    }
}