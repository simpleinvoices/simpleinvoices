<?php
/**
 * @author Rich
 * Jul 15, 2016
 */
class ProductAttributes {

    public static function get($id) {
        // @formatter:off
        $sql = "SELECT pa.*, pat.name AS `type`
                FROM " . TB_PREFIX . "products_attributes pa
                LEFT JOIN " . TB_PREFIX . "products_attribute_type pat ON (pa.type_id = pat.id)
                WHERE pa.id = :id";
        // @formatter:on
        $sth = dbQuery($sql, ':id', $id);
        return ($sth->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function getName($id) {
        $sql = "SELECT * FROM " . TB_PREFIX . "products_attributes WHERE id = :id";
        $sth = dbQuery($sql, ':id', $id);
        $attribute = $sth->fetch(PDO::FETCH_ASSOC);
        return $attribute['name'];
    }

    public static function getType($id) {
        $attribute = self::get($id);
        return $attribute['type'];
    }

    public static function getValue($attribute_id, $value_id) {
        $type = self::getType($attribute_id);
        if ($type == 'list') {
            $sql = "SELECT value FROM " . TB_PREFIX . "products_values WHERE id = :id";
            $sth = dbQuery($sql, ':id', $value_id);
            $attribute = $sth->fetch(PDO::FETCH_ASSOC);
            return $attribute['value'];
        }
        return $value_id;
    }

    public static function getVisible($id) {
        $sql = "SELECT visible FROM " . TB_PREFIX . "products_attributes WHERE id = :id";
        $sth = dbQuery($sql, ':id', $id);
        $attribute = $sth->fetch(PDO::FETCH_ASSOC);
        return ($attribute['visible'] == '1');
    }

    public static function getAll() {
        $sql = "SELECT * FROM " . TB_PREFIX . "products_attributes";
        $sth = dbQuery($sql);
        return ($sth->fetchAll(PDO::FETCH_ASSOC));
    }
}
