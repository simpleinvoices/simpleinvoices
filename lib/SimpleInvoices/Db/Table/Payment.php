<?php
class SimpleInvoices_Db_Table_Payment extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "payment";
    protected $_primary = array('domain_id', 'id');

    public function insert($data)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $data['domain_id'] = $auth_session->domain_id;
        
        return parent::insert($data);
    }
    
    public function update($data, $id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('id = ?', $id);
        $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        
        // Call parent
        parent::update($data, $where);
    }
}
?>
