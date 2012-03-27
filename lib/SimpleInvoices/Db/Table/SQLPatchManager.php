<?php
class SimpleInvoices_Db_Table_SQLPatchManager extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "sql_patchmanager";
    protected $_primary = array('sql_id');
    
    public function getCount()
    {
        $select = $this->select();
        $select->from($this, array('count' => new Zend_Db_Expr('COUNT(sql_patch)')));
        
        $row = $this->getAdapter()->fetchRow($select);
        
        if ($row) return($row['count']);
        else return 0;
    }
    
    /**
    * Static method to get the number of SQL Patches
    * 
    */
    public static function getNumberOfDoneSQLPatches()
    {
        $table = new self();
        return $table->getCount();
    }
}
?>
