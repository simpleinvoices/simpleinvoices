<?php
class GroupBy {
    private $fields;

    /**
     * GroupBy class constructor
     * @param DbField $field Highest priority fields to <b>GROUP BY</b>.
     */
    public function __construct($field) {
        if (is_array($field)) $this->fields = $field;
        else $this->fields = array($field);
    }

    /**
     * Build the <b>GROUP BY</b> statement.
     * @return string <b>GROUP BY</b> statment built from specified fields.
     */
    public function build() {
        $stmt = "GROUP BY ";
        $first = true;
        foreach ($this->fields as $field) {
            if ($first) $first = false;
            else $stmt .= ", ";
            $stmt .= PdoDb::formatField($field);
        }
        return $stmt;
    }
}