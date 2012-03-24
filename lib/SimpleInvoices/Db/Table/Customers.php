<?php
class SimpleInvoices_Db_Table_Customers extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "customers";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Find a customer by the given ID
    * 
    * @param mixed $id
    * @return Zend_Db_Table_Rowset_Abstract
    */
    public function find($id)
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $customer =  $this->getAdapter()->fetchRow($select);
        if ($customer) {
            $customer['wording_for_enabled'] = $customer['enabled']==1?$LANG['enabled']:$LANG['disabled'];
        }
        
        return $customer;
    }
    
    /**
    * Insert a new customer
    * 
    * @param mixed $data
    * @return array
    */
    public function insert($data)
    {
        // Set the domain ID
        $auth_session = Zend_Registry::get('auth_session');
        $data['domain_id'] = $auth_session->domain_id;
        
        return parent::insert($data);
    }
    
    /**
    * Update a customer
    * 
    * @param mixed $data
    * @param mixed $id
    * @return int
    */
    public function update(array $data, $id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('id = ?', $id);
        $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        
        return parent::update($data, $where);
    }
}
?>
