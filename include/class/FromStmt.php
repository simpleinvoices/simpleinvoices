<?php
class FromStmt {
    private $table;

    /**
     * Class constructor
     * @param string $table Table name.
     * @param string $alias Alias to be assigned to this table.
     */
    public function __construct($table, $alias=null) {
        $this->table = array();
        $this->addTable($table, $alias);
    }

    /**
     * Add a table to the <b>FROM</b> list.
     * @param string $table 
     * @param string $alias
     */
    public function addTable($table, $alias=null) {
        $this->table[] = array($table, $alias);
    }

    /**
     * Build <b>FROM</b> statement.
     * @param array $keypairs (Optional) Parameter exists for function call compatibility
     *        with other <i>PdoDb</i> class SQL build objects. 
     * @return string
     */
    public function build($keypairs = null) {
        $stmt = "FROM ";
        $first = true;
        foreach ($this->table as $table) {
            if ($first) $first = false;
            else $stmt .= ", ";
            $stmt .= PdoDb::formatField(PdoDb::addTbPrefix($table[0]));
            if (!empty($table[1])) $stmt .= " " . PdoDb::formatField($table[1]);
        }
        return $stmt;
    }
}
