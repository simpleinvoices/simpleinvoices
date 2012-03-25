<?php
class SimpleInvoices_Db_Table_InvoiceItems extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "invoice_items";
    protected $_primary = array('id');

    /**
    * Get the invoice total
    * 
    * @param mixed $invoice_id
    */
    public function getInvoiceTotal($invoice_id)
    {
        $select = $this->select();
        $select->from($this->_name, array('total' => new Zend_Db_Expr("SUM(total)")));
        $select->where('invoice_id = ?', $invoice_id);
        
        $result = $this->getAdapter()->fetchRow($select);
        
        if ($result) return $result['total'];
        else return 0.0;
    }
    
    /**
    * Get overall totals for an invoice
    */
    public function getTotals($invoice_id)
    {
        $select = $this->select();
        $select->from($this->_name, array(
            'total_tax' => new Zend_Db_Expr("SUM(tax_amount)") , 
            'total'     => new Zend_Db_Expr("SUM(total)")
        ));
        
        $select->where('invoice_id = ?', $invoice_id);
        
        return $this->getAdapter()->fetchRow($select);
    }
    
    
}
?>
