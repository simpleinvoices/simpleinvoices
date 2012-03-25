<?php

class SimpleInvoices_Db_Table_CustomFields extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "custom_fields";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Fetch all custom field labels
    * 
    */
    public function fetchDisplays()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->order('cf_custom_field');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $customFields = array();
    
        foreach ($result as $customField) {
            $customFields[$customField['cf_custom_field']] = $customField['cf_display'];
        }

        return $customFields;
    }
    
    /**
    * Fetch all custom field labels
    * 
    */
    public function fetchLabels()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->order('cf_custom_field');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $customFields = array();
        
        $i = 0;
        foreach ($result as $customField) {
            // If not set, don't show
            if (!empty($customField['cf_custom_label'])) {
                $customFields[$customField['cf_custom_field']] = $customField['cf_custom_label'];
                $customFields[$customField['cf_custom_field']] = $LANG["custom_field"].' '.($i%4+1);
            }
            $i++;
        }

        return $customFields;
    }
}
?>
