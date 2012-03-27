<?php
class SimpleInvoices_Db_Table_Customers extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "customers";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Fetch all customers
    */
    public function fetchAll()
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->order('name');
        
        return $this->getAdapter()->fetchAll($select);    
    }
    
    /**
    * Fetch all active customers
    * 
    */
    public function fetchAllActive()
    {   
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->where('enabled = 1');
        $select->order('name');
        
        return $this->getAdapter()->fetchAll($select);
    }
    
    /**
    * Find a customer by the given ID
    * 
    * @param mixed $id
    * @return Zend_Db_Table_Rowset_Abstract
    */
    public function getCustomerById($id)
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
    * Get default customer
    * 
    */
    public function getDefault()
    {
        $tbl_system_defaults = Zend_Registry::get('tbl_prefix') . 'system_defaults';
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this->_name)
            ->joinInner($tbl_system_defaults, $tbl_system_defaults.'.value = ' . $this->_name . '.id', array());
        $select->where($tbl_system_defaults . ".name = ?", "customer");
        $select->where($this->_name . '.domain_id = ?', $auth_session->domain_id);
        
        return $this->getAdapter()->fetchRow($select);
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
        
        // IF Credit Card Number is present it must be cyphered
        if (array_key_exists('credit_card_number', $data)) {
            if (!empty($data['credit_card_number'])) {
                $config = Zend_Registry::get('config');
            
                $enc = new encryption();
                $key = $config->encryption->default->key;
                $data['credit_card_number'] = $enc->encrypt($key, $data['credit_card_number']);   
            }
        }
        
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
        
        // IF Credit Card Number is present it must be cyphered
        if (array_key_exists('credit_card_number', $data)) {
            if (!empty($data['credit_card_number'])) {
                $config = Zend_Registry::get('config');
            
                $enc = new encryption();
                $key = $config->encryption->default->key;
                $data['credit_card_number'] = $enc->encrypt($key, $data['credit_card_number']);   
            }
        }
        
        return parent::update($data, $where);
    }
}
?>
