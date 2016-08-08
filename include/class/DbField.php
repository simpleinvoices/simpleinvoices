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
     * Generate the parameter for this field to use in SQL statements.
     * @return string Field name enacapsulated in back-tic for use in SQL statement.
     */
    public function genParm() {
        return PdoDb::formatField($this->field);
    }
}
