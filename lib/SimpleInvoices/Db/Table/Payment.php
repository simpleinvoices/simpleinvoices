<?php
class SimpleInvoices_Db_Table_Payment extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "payment";
    protected $_primary = array('domain_id', 'id');

    public function insert($data)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $data['domain_id'] = $auth_session->domain_id;
        
        return parent::insert($data);
    }
    
    public function update($data, $id)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('id = ?', $id);
        $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
        
        // Call parent
        parent::update($data, $where);
    }
    
    /**
     * Replaces calc_invoice_paid()
     * 
     * @param int $invoice
     */
	public function getPaidAmountForInvoice($invoice) 
	{
		$select = new Zend_Db_Select($this->getAdapter());
		$select->from($this->_name, array('amount' => new Zend_Db_Expr("COALESCE(SUM(ac_amount), 0)")));
		$select->where('ac_inv_id=?', $invoice);
		
		return $this->getAdapter()->fetchOne($select);
	}
}
?>
