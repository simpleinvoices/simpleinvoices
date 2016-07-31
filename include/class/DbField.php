<?php
require_once "include/class/PdoDbException.php";
/**
 * DbField class
 * @author Rich
 * Jul 21, 2016
 */
class DbField {
    private $field;

    /**
     * Class constructor
     * @param string $field Field name
     */
    public function __construct($field) {
        $this->field = $field;
    }

    /**
     * Static function to test for object of this class type.
     * @param mixed $obj Object to test.
     * @return boolean <b>true</b> if it is a <i>DbField</i> type object. Otherwise <b>false</b>.
     */
    public static function isField($obj) {
        return is_a($obj, "DbField");
    }

    /**
     * Generate the parameter for this field to use in SQL statements.
     * @return string Field name enacapsulated in back-tic for use in SQL statement.
     */
    public function genParm() {
        return PdoDb::formatField($this->field);
    }
}
