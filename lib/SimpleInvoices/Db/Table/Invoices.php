<?php
class SimpleInvoices_Db_Table_Invoices extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "invoices";
    protected $_primary = array('domain_id', 'id');
    protected $_last_inserted_id;
    
    /**
    * Get an invoice by ID
    */
    public function getInvoice($id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->where('id = ?', $id);
        $select->where('domain_id = ?', $auth_session->domain_id);
        
        return $this->getAdapter()->fetchRow($select);    
    }
    
    /**
     * Insert an Invoice
     * 
     * ToDo: missing check foreign keys
     * 
     * @see lib/Zend/Db/Table/Zend_Db_Table_Abstract::insert()
     */
    public function insert($data)
    {
    	$SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();
    	$pref_group= $SI_PREFERENCES->getPreferenceById($_POST['preference_id']);
    	
    	$auth_session = Zend_Registry::get('auth_session');
    	
    	$data['domain_id'] = $auth_session->domain_id;
    	$data['index_id'] = SimpleInvoices_Db_Table_Index::NEXT('invoice',$pref_group['index_group']);
    	
    	$result = parent::insert($data);
    	$this->_last_inserted_id = $this->getAdapter()->lastInsertId($this->_name, 'id');
    	
    	// Increment Index if inserted
    	if ($result) SimpleInvoices_Db_Table_Index::INCREMENT('invoice',$pref_group['index_group']);
    	
    	return $result;
    }
    
    /**
    * Update
    */
    public function update($data, $id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('id = ?', $id);
        $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        
        return parent::update($data, $where);
    }
    
    public function getLastInsertId()
    {
    	return $this->_last_inserted_id;
    }
}
?>
