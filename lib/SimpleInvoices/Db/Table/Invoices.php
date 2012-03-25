<?php
class SimpleInvoices_Db_Table_Invoices extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "invoices";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Get an invoice by ID
    */
    public function getInvoice($id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        return $this->getAdapter()->fetchRow($select);    
    }
    
    
    /**
    * Update
    */
    public function update($data, $id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('id = ?', $id);
        $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        
        return parent::update($data, $where);
    }
}
?>
