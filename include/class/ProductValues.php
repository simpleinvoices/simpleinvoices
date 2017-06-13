<?php

class ProductValues
{
    /**
     * Get count of products_values records.
     * @return unknown
     */
    public static function count()
    {
        global $pdoDb;
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $attribute = $pdoDb->request("SELECT", "products_values");
        return $rows[0]['count'];
    }

    /**
     * Get the value for a specified product attribute and value ID.
     *
     * @param string $attribute_id
     *            Product attribute.
     * @param string $value_id
     *            Product value ID.
     * @return string If <b>attribute_id</b> is for a type, <i>list</i>, product,
     *         return the value from the <i>products_values</i> record for the
     *         specified <b>value_id</b>. Otherwise return the <b>value_id</b>
     *         parameter.
     */
    public static function getValue($attribute_id, $value_id)
    {
        global $pdoDb;
        $type = self::getType($attribute_id);
        if ($type == 'list') {
            $pdoDb->addSimpleWhere("id", $value_id);
            $pdoDb->setSelectList("value");
            $attribute = $pdoDb->request("SELECT", "products_values");
            return $attribute['value'];
        }
        return $value_id;
    }
}