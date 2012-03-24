<?php
class SimpleInvoices_Db_Table_Products extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "products";
    protected $_primary = array('domain_id', 'id');
    
    /**
    * Delete method
    * 
    * @param mixed $id
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
    * Replaces getProducts
    * 
    */
    public function fetchAll()
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->where('visible = 1');
        $select()->order('description');
        
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
    * Replaces getProduct
    */
    public function find($id)
    {
        global $LANG;
        
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $product = $this->getAdapter()->fetchRow($select);
        
        $product['wording_for_enabled'] = $product['enabled']==1?$LANG['enabled']:$LANG['disabled'];
        return $product;
    }
    
    /**
    * Replaces getActiveProduts
    *     
    */
    public function findActive()
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('enabled = 1');
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->order('description');
        
        $result = $this->getAdapter()->fetchAll($select);
        
        return $result;
    }
    
    /**
    * Replaces Product::count
    * 
    */
    public function getCount()
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this, array('count(id) as amount'));
        //$select->where('enabled');
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        $row = $this->getAdapter()->fetchRow($select);
        
        return($row['amount']);
    }
    
    public function getLastInsertId()
    {
        return $this->getAdapter()->lastInsertId();
    }
    
    /**
    * Replaces insertProduct and insertProductComplete
    * 
    * @param mixed $data
    * @return array
    */
    public function insert($data)
    {
        // Set the domain ID
        $auth_session = Zend_Registry::get('auth_session');
        $data['domain_id'] = $auth_session->domain_id;
     
        // ToDo: Is this really needed?
        if (!isset($data['enabled'])) $data['enabled'] = 1;
        if (!isset($data['visible'])) $data['visible'] = 1;
        
        return parent::insert($data);
    }
    
    /**
    * Replaces updateProduct
    * 
    * @param int $data
    * @param mixed $where
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
