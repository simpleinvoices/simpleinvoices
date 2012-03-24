<?php

class SimpleInvoices_Db_Table_Extensions extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "extensions";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Get all extensions
    * 
    */
    public function fetchAll()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $domains = array();
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', 0);
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        $select->where("(" . implode(' OR ', $domains) . ")");
        $select->order('name');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        $products = array();

        foreach ($result as $product) {
            if ($product['enabled'] == 1) {
                $product['enabled'] = $LANG['enabled'];
            } else {
                $product['enabled'] = $LANG['disabled'];
            }

            // Add to products array
            $products[] = $product;
        }

        return $products;
    }
    
    /**
    * Find an extension by id
    */
    public function find($id)
    {
        $auth_session = Zend_Registry::get('auth_session');

        $select = $this->select();
        $select->where('id = ?', $id);
        
        $domains = array();
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', 0);
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        $select->where("(" . implode(' OR ', $domains) . ")");
        
        $extension = $this->getAdapter()->fetchRow($select);
        
        return $extension;
    }
    
    /**
    * Replaces getExtensionID
    * 
    * @param mixed $name
    * @return mixed
    */
    public function findByName($name)
    {
        $auth_session = Zend_Registry::get('auth_session');

        $select = $this->select();
        $select->where('name = ?', $name);
        
        $domains = array();
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', 0);
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        $select->where("(" . implode(' OR ', $domains) . ")");
        
        $extension = $this->getAdapter()->fetchRow($select);
        
        if (!$extension) return -2;                      // -2 = no result set = extension not found
        if ($extension['enabled'] == 0) return -1;      // -1 = extension not enabled
        return $extension['id'];
    }
    
    /**
    * Get count
    * 
    */
    public function getCount()
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this, array('count(id) as amount'));
        $domains = array();
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', 0);
        $domains[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        $select->where("(" . implode(' OR ', $domains) . ")");
        
        $row = $this->getAdapter()->fetchRow($select);
        
        return($row['amount']);
    }
    
    /**
    * Get status of an extension
    */
    public function getStatus($id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $result = $this->getAdapter()->fetchRow($select);
        return $result['enabled'];
    }
    
    /**
    * Set the extension status
    */
    public function setStatus(bool $status, $id)
    {
        if ($status) $enabled = 1;
        else $enabled = 0;
        
        return $this->update(array('enabled' => $enabled), $id);
    }
    
    /**
    * Toggle status of an extension
    */
    public function toggleStatus($id)
    {
        $status = $this->getStatus($id);
        $enabled = 1 - $status;
        // Just in case...
        if ($enabled < 0) $enabled = 0;
        
        // Update
        return $this->update(array('enabled' => $enabled), $id);
    }
    
    /**
    * Update extension
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
