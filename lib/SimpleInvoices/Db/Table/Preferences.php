<?php
class SimpleInvoices_Db_Table_Preferences extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "preferences";
    protected $_primary = array('domain_id', 'pref_id');
    
    /**
    * Fetch all preferences
    * 
    */
    public function fetchAll()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->order('pref_description');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $preferences = array();

        foreach ($result as $preference) {
            if ($preference['pref_enabled'] == 1) {
                $preference['enabled'] = $LANG['enabled'];
            } else {
                $preference['enabled'] = $LANG['disabled'];
            }

            // Add to preferences array
            $preferences[] = $preference;
        }

        return $preferences;
    }
    
    /**
    * Fetch all preferences that are active
    * 
    */
    public function fetchAllActive()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->where('pref_enabled = ?', 1);
        $select->order('pref_description');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $preferences = array();

        foreach ($result as $preference) {
            $preference['enabled'] = $LANG['enabled'];
        
            // Add to preferences array
            $preferences[] = $preference;
        }

        return $preferences;
    }
    
    
    
    public function getPreferenceById($id)
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('pref_id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $preference =  $this->getAdapter()->fetchRow($select);
        if ($preference) {
            $preference['status_wording'] = $preference['status']==1?$LANG['real']:$LANG['draft'];
            $preference['enabled'] = $preference['pref_enabled']==1?$LANG['enabled']:$LANG['disabled'];
        }
        
        return $preference;
    }
    
    /**
    * Get default preference
    * 
    */
    public function getDefault()
    {
        $tbl_system_defaults = Zend_Registry::get('tbl_prefix') . 'system_defaults';
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this->_name)
            ->joinInner($tbl_system_defaults, $tbl_system_defaults.'.value = ' . $this->_name . '.pref_id', array());
        $select->where($tbl_system_defaults . ".name = ?", "preference");
        $select->where($this->_name . '.domain_id = ?', $auth_session->domain_id);
        
        return $this->getAdapter()->fetchRow($select);
    }
    
    /**
    * Insert a new preference
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
    * Update a preference
    * 
    * @param mixed $data
    * @param mixed $id
    * @return int
    */
    public function update(array $data, $id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('pref_id = ?', $id);
        $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        
        return parent::update($data, $where);
    }
}
?>
