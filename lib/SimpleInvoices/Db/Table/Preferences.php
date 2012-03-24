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
    
    
    /**
    * Find a preference by the given ID
    * 
    * @param mixed $id
    * @return Zend_Db_Table_Rowset_Abstract
    */
    public function find($id)
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
