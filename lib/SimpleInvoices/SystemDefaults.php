<?php
class SimpleInvoices_SystemDefaults extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "system_defaults";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Delete method
    * 
    * @param mixed $id
    * @return int
    */
    public function delete($id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('id = ?', $id);
        $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        
        parent::delete($where);
    }
    
    /**
    * Replaces getSystemDefaults
    */
    public function fetchAll()
    {
        $tbl_extensions = Zend_Registry::get('tbl_prefix') . 'extensions';
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this->_name, array('name', 'value'))
            ->joinInner($tbl_extensions, $tbl_extensions.'.id = ' . $this->_name . '.extension_id', array());
        $select->where($tbl_extensions . ".enabled = 1");
        $select->where($this->_name . '.domain_id = ?', $auth_session->domain_id);
        $select->order($this->_name . '.extension_id ASC'); // order is important for overriding settings
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $default = array();
        
        foreach($result as $row) {
            $default[$row['name']] = $row['value'];    
        }
        
        return $default;
    }
    
    /**
    * Fetch all system defaults for a given extension
    * 
    */
    public function fetchAllForExtension($extension_id)
    {
        $tbl_extensions = Zend_Registry::get('tbl_prefix') . 'extensions';
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this->_name, array('name', 'value'))
            ->joinInner($tbl_extensions, $tbl_extensions.'.id = ' . $this->_name . '.extension_id', array());
        $select->where($tbl_extensions . ".enabled = 1");
        $select->where($this->_name . '.domain_id = ?', $auth_session->domain_id);
        $select->where($this->_name . '.extension_id = ?', $extension_id);
        $select->order($this->_name . '.extension_id ASC'); // order is important for overriding settings
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $default = array();
        
        foreach($result as $row) {
            $default[$row['name']] = $row['value'];    
        }
        
        return $default;
    }
    
    /**
    * Get a system default value
    * 
    * @param mixed $name
    * @return mixed
    */
    public function findByName($name)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('name = ?', $name);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $result = $this->getAdapter()->fetchRow($select);
        return $result['value'];
    }
    
    /**
    * Replaces updateDefault
    * 
    * @param mixed $data
    * @param mixed $id
    * @return int
    */
    public function update($name, $value, $extension_name = "core")
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('name = ?', $name);
        
        $extensions = new SimpleInvoices_Extensions();
        $extension_id = $extensions->findByName($extension_name);
        if ($extension_id >= 0) {
            $where[] = $this->getAdapter()->quoteInto('extension_id = ?', $extension_id);
        } else {
            throw new Exception("Invalid extension name: ".str_replace(array('{', '}', '+'), array('&#123','&#125', '&#43;'), htmlentities($extension_name, ENT_QUOTES, 'UTF-8')));
            exit(1);
        }
        
        return parent::update(array('value' => $value), $where);
    }

}
?>
