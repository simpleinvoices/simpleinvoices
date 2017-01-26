<?php
require_once "include/class/PdoDbException.php";
/**
 * DbField class
 * @author Rich
 * Jul 21, 2016
 */
class DbField {
    private $alias;
    private $field;

    /**
     * Class constructor
     * @param string $field Field name
     * @param string $alias (Optional) Alias for this field. Specify only if needed.
     */
    public function __construct($field, $alias="") {
        $this->field = $field;
        $this->alias = $alias;
    }

    /**
     * Generate the parameter for this field to use in SQL statements.
     * @return string Field name enacapsulated in back-tic for use in SQL statement.
     */
    public function genParm() {
        $result = PdoDb::formatField($this->field);
        if (!empty($this->alias)) $result .= " AS " . $this->alias;
        return $result;
    }
}
