<?php


class SimpleInvoices_Payment
{
	/**
	 * Database object.
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;
	
	/**
	 * Payment indentifier.
	 * @var int
	 */
	private $_id;
	
	/**
	 * Payment data.
	 * @var array
	 */
	private $_data;
	
	public function __construct($payment)
	{
		$this->_id = $payment;
		$this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
		
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
	 * Initialize payment data.
	 * replaces method getPayment($id)
	 */
	protected function _initData()
	{
		$tbl_prefix = SimpleInvoices_Db_Table_Abstract::getTablePrefix();
		
		$select = new Zend_Db_Select($this->_db);
		$select->from($tbl_prefix . 'payment');
		$select->joinInner($tbl_prefix . 'invoices', $tbl_prefix . "payment.ac_inv_id = " . $tbl_prefix . "invoices.id", NULL);
		$select->joinInner($tbl_prefix . 'customers', $tbl_prefix . "invoices.customer_id = " . $tbl_prefix . "customers.id", array('customer_id' => $tbl_prefix . "customers.id", 'customer' => $tbl_prefix . "customers.name"));
		$select->joinInner($tbl_prefix . 'biller', $tbl_prefix . "invoices.biller_id = " . $tbl_prefix . "biller.id", array('biller_id' => $tbl_prefix . "biller.id", 'biller' => $tbl_prefix . "biller.name"));
		$select->where($tbl_prefix . 'payment.id=?', $this->_id);
		
		$this->_data = $this->_db->fetchRow($select);
		$this->_data['date'] = siLocal::date($payment['ac_date']);
	}
	
	/**
	 * Get the invoice for this payment.
	 * @return SimpleInvoices_Invoice
	 */
	public function getInvoice()
	{	
		return new SimpleInvoices_Invoice($this->_data['ac_inv_id']);
	}
	
	
	public function getType()
	{
		$paymentTypes = new SimpleInvoices_Db_Table_PaymentTypes();
		return $paymentTypes->getPaymentTypeById($this->_data['ac_payment_type']);
	}
	
	public function toArray()
	{
		return $this->_data;
	}
}