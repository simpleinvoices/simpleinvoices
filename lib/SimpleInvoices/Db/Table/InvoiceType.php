<?php
class SimpleInvoices_Db_Table_InvoiceType extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "invoice_type";
    protected $_primary = array('inv_ty_id');

    /**
    * Get Invoice type by id
    * 
    * @param mixed $id
    * @return mixed
    */
    public function getInvoiceType($id)
    {
        $select = $this->select();
        $select->where('inv_ty_id = ?', $id);
        
        return $this->getAdapter()->fetchRow($select);
    }
}
?>
