<?php
class SimpleInvoices_Db_Table_Tax extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "tax";
    protected $_primary = array('domain_id', 'tax_id');
 
    /**
    * Fetch all available taxes
    * 
    */
    public function fetchAll()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->order('tax_description');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $taxes = array();

        foreach ($result as $tax) {
            if ($tax['tax_enabled'] == 1) {
                $tax['enabled'] = $LANG['enabled'];
            } else {
                $tax['enabled'] = $LANG['disabled'];
            }

            // Add to products array
            $taxes[] = $tax;
        }

        return $taxes;
    }
    
    /**
    * Fetch all enabled taxes
    * 
    */
    public function fetchAllActive()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->where('tax_enabled = ?', 1);
        $select->order('tax_description');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $taxes = array();

        foreach ($result as $tax) {
            $tax['enabled'] = $LANG['enabled'];
 
            // Add to products array
            $taxes[] = $tax;
        }

        return $taxes;
    }
    
    /**
    * Find a given tax given it's ID   
    */
    public function getTaxRateById($id)
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('tax_id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $tax = $this->getAdapter()->fetchRow($select);
        
        if ($tax) {
            $tax['enabled'] = $tax['tax_enabled'] == 1 ? $LANG['enabled']:$LANG['disabled'];
        }
        
        return $tax;
    }
    
    /**
    * Get default tax
    * 
    */
    public function getDefault()
    {
        $tbl_system_defaults = Zend_Registry::get('tbl_prefix') . 'system_defaults';
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this->_name)
            ->joinInner($tbl_system_defaults, $tbl_system_defaults.'.value = ' . $this->_name . '.tax_id', array());
        $select->where($tbl_system_defaults . ".name = ?", "tax");
        $select->where($this->_name . '.domain_id = ?', $auth_session->domain_id);
        
        return $this->getAdapter()->fetchRow($select);
    }
    
    public function insert($data)
    {
        // Set the domain ID
        $auth_session = Zend_Registry::get('auth_session');
        $data['domain_id'] = $auth_session->domain_id;
     
        
        return parent::insert($data);
    }
}
?>
