<?php
class SimpleInvoices_Db_Table_Biller extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "biller";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Fetch all active customers
    * 
    */
    public function fetchAll()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->order('name');
        
        $result =  $this->getAdapter()->fetchAll($select);
        
        $billers = array();

        foreach ($result as $biller) {
            if ($biller['enabled'] == 1) {
                $biller['enabled'] = $LANG['enabled'];
            } else {
                $biller['enabled'] = $LANG['disabled'];
            }
          
            // Add to billers array
            $billers[] = $biller;
        }

        return $billers;
    }
    
    /**
    * Fetch all active billers
    * 
    */
    public function fetchAllActive()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->where('enabled = 1');
        $select->order('name');
        
        return $this->getAdapter()->fetchAll($select);
    }
    
    /**
    * Find a biller by the given ID
    * 
    * @param mixed $id
    * @return Zend_Db_Table_Rowset_Abstract
    */
    public function getBiller($id)
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $biller = $this->getAdapter()->fetchRow($select);
        if ($biller) {
            $biller['wording_for_enabled'] = $biller['enabled']==1?$LANG['enabled']:$LANG['disabled'];
        }
        
        return $biller;
    }
    
    /**
    * Get default biller
    * 
    */
    public function getDefault()
    {
        $tbl_system_defaults = Zend_Registry::get('tbl_prefix') . 'system_defaults';
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this->_name)
            ->joinInner($tbl_system_defaults, $tbl_system_defaults.'.value = ' . $this->_name . '.id', array());
        $select->where($tbl_system_defaults . ".name = ?", "biller");
        $select->where($this->_name . '.domain_id = ?', $auth_session->domain_id);
        
        return $this->getAdapter()->fetchRow($select);
    }
    
    /**
    * Insert a new biller
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
    * Update a biller
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
