<?php
/**
 *  ProductAttributes class.
 *  @author Richard Rowley
 *
 *  Last modified:
 *      2016-08-15
 */
class ProductAttributes {

    /**
     * Get <i>products_attributes</i> information for a specified <b>id</b>.
     * @param string $id ID of record to retrive.
     * @return array Associative array for record retrieved.
     */
    public static function get($id) {
        global $pdoDb;
        $pdoDb->setSelectList(array("pa.*", "pat.name AS type"));
        $pdoDb->addSimpleWhere("pd.id", $id);
        $oc = new OnClause(new OnItem(false, "pa.type_id", "=", new DbField("pat.id"), false));
        $pdoDb->addToJoins("LEFT", "products_attribute_type", "pat", $oc);
        $result = $pdoDb->request("SELECT", "products_attributes", "pa");
error_log("ProductAttributes - get() - id[$id] result - " . print_r($result,true));
        return $result;
    }

    /**
     * Get the <i>product_attribute</i> name for the specified <b>id</b>.
     * @param string $id ID for the record to access.
     * @return string <b>name</b> setting for the specified record.
     */
    public static function getName($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("id", $id);
        $pdoDb->setSelectList("name");
        $attribute = $pdoDb->request("SELECT","products_attributes");
error_log("ProductAttributes - getName - id[$id] addribute - " . print_r($attribute,true));
        return $attribute['name'];
    }

    /**
     * Get the <i>product_attribute</i> type for the specified <b>id</b>.
     * @param string $id ID for record to access.
     * @return string <b>type</b> setting for the specified record.
     */
    public static function getType($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("id", $id);
        $pdoDb->setSelectList("type");
        $attribute = $pdoDb->request("SELECT","products_attributes");
error_log("ProductAttributes - getType - id[$id] addribute - " . print_r($attribute,true));
        return $attribute['type'];
    }

    /**
     * Get the value for a specified product attribute and value ID.
     * @param string $attribute_id Product attribute.
     * @param string $value_id Product value ID.
     * @return string If <b>attribute_id</b> is for a type, <i>list</i>, product,
     *         return the value from the <i>product_values</i> record for the
     *         specified <b>value_id</b>. Otherwise return the <b>value_id</b>
     *         parameter.
     */
    public static function getValue($attribute_id, $value_id) {
        global $pdoDb;
        $type = self::getType($attribute_id);
        if ($type == 'list') {
            $pdoDb->addSimpleWhere("id", $value_id);
            $pdoDb->setSelectList("value");
            $attribute = $pdoDb->request("SELECT", "product_values");
error_log("ProductAttributes - getValue - attribute_id[$attribute_id] value_id[$value_id] addribute - " . print_r($attribute,true));
            return $attribute['value'];
        }
        return $value_id;
    }

    /**
     * Determine if a <b>product_attribute</b> is flagged as visible.
     * @param string $id ID of record to check.
     * @return boolean <b>true</b> if record is visible; <b>false</b> if not.
     */
    public static function getVisible($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("id", $id);
        $pdoDb->setSelectList("visible");
        $attribute = $pdoDb->request("SELECT", "products_attributes");
error_log("ProductAttributes - getVisible() - id[$id] addribute - " . print_r($attribute,true));
        return ($attribute['visible'] == ENABLED);
    }

    /**
     * Get all <b>products_attributes</b> records
     * @return array Rows from table.
     */
    public static function getAll() {
        global $pdoDb;
        $result = $pdoDb->request("SELECT", "products_attributes");
error_log("ProductAttributes - getAll() - result - " . print_r($result,true));
        return $result;
    }
}
