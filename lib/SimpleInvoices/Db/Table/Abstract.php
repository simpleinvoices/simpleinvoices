<?php
class SimpleInvoices_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    
    /**
     * This will automatically set table name with prefix from bootstrap file
     * @return void
     */
    protected function _setupTableName()
    {
        parent::_setupTableName(); 
        if (Zend_Registry::isRegistered('tbl_prefix')) {
            $this->_name = Zend_Registry::get('tbl_prefix') . $this->_name;
        }
    }
}
?>
