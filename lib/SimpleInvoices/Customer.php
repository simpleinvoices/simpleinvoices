<?php

class SimpleInvoices_Customer
{
	/**
	 * Database object.
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;
	
	/**
	 * Customer identifier.
	 * @var int
	 */
	private $_id;
	
	/**
	 * Customer data.
	 * @var array
	 */
	private $_data;
	
	
	public function __construct($customer)
	{
		// ToDo: Pass configuration options
		$this->_db = Zend_Db_Table::getDefaultAdapter();
		$this->_id = $customer;
		
		$this->_initData();
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->_data)) {
			return $this->_data[$name];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Initializes invoice data.
	 * This method is equivalent to the old getInvoice()
	 */
	protected function _initData()
	{
		$customers = new SimpleInvoices_Db_Table_Customers();
		$this->_data = $customers->getCustomerById($this->_id);
		unset($this->_data['id']);
	}
	
	/**
	 * Get the customer identifier.
	 * @return int
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Get the total amount payed by this user.
	 * Replaces: calc_customer_total()
	 */
	public function getTotal()
	{
		$tbl_prefix = SimpleInvoices_Db_Table_Abstract::getTablePrefix();
		
		$select = new Zend_Db_Select($this->_db);
		$select->from($tbl_prefix  . "invoice_items", array('total' => new Zend_Db_Expr("COALESCE(SUM(" . $tbl_prefix . "invoice_items.total), 0)")));
		$select->joinInner($tbl_prefix . "invoices", $tbl_prefix . "invoice_items.invoice_id=" . $tbl_prefix . "invoices.id", NULL);
		$select->where($tbl_prefix . "invoices.customer_id=?", $this->_id);
		
		return $this->_db->fetchOne($select);		
	}

	/**
	 * Get the total paid amount by this customer.
	 * Replaces: calc_customer_paid()
	 * Enter description here ...
	 */
	public function getPaidAmount()
	{
		$tbl_prefix = SimpleInvoices_Db_Table_Abstract::getTablePrefix();
		
		$select = new Zend_Db_Select($this->_db);
		$select->from($tbl_prefix  . "payment", array('amount' => new Zend_Db_Expr("COALESCE(SUM(" . $tbl_prefix . "payment.ac_amount), 0)")));
		$select->joinInner($tbl_prefix . "invoices", $tbl_prefix . "payment.ac_inv_id=" . $tbl_prefix . "invoices.id", NULL);
		$select->where($tbl_prefix . "invoices.customer_id=?", $this->_id);
		
		return $this->_db->fetchOne($select);
	}
	
	/**
     * Backward compatibility
     * @return array
     */
    public function toArray()
    {
    	return $this->_data;
    }
}