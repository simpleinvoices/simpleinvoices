<?php


class SimpleInvoices_Invoice
{
	/**
	 * Database object.
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;
	
	/**
	 * Invoice indentifier.
	 * @var int
	 */
	private $_id;
	
	/**
	 * Invoice data.
	 * It MUST strictly contain databse fields as I'll use this for updates and inserts.
	 * @var array
	 */
	private $_data;
	private $_outData;
	
	/**
	 * Biller information
	 * @var SimpleInvoices_Biller
	 */
	private $_biller = null;
	
	public function __construct($invoice)
	{
		// ToDo: Pass configuration options
		$this->_db = Zend_Db_Table::getDefaultAdapter();
		$this->_id = $invoice;
		
		$this->_initData();
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->_outData)) {
			return $this->_outData[$name];
		}elseif (array_key_exists($name, $this->_data)) {
			return $this->_data[$name];
		} else {
			return NULL;
		}
	}
	
	public function __set($name, $value)
	{
		if( array_key_exists($name, $this->_data)) {
			$this->_data[$name] = $value;
		}
		$this->_outData[$name] = $value;
	}
	
	/**
	 * Initializes invoice data.
	 * This method is equivalent to the old getInvoice()
	 */
	protected function _initData()
	{
		$invoices = new SimpleInvoices_Db_Table_Invoices();
	    $invoiceItems = new SimpleInvoices_Db_Table_InvoiceItems();
	    $payments = new SimpleInvoices_Db_Table_Payment();
	   
	    $this->_data = $invoices->getInvoice($this->_id);
	    if (!is_array($this->_data)) {
	    	require_once 'SimpleInvoices/Exception.php';
	    	throw new SimpleInvoices_Exception('Invalid invoice identifier.');
	    }
	    
	    // I unset the ID as I don't want it to be present in inserts or updates.
	    unset($this->_data['id']);
	    
		$this->_outData = array();
		
		$this->_outData['calc_date'] = date('Y-m-d', strtotime( $this->_data['date'] ) );
		$this->_outData['date'] = siLocal::date( $this->_data['date'] );
		$this->_outData['total'] = $invoiceItems->getInvoiceTotal($this->_id);
		$this->_outData['gross'] = $invoiceItems->getGrossForInvoice($this->_id);
		$this->_outData['paid'] = $payments->getPaidAmountForInvoice($this->_id);
		$this->_outData['owing'] = $this->_outData['total'] - $this->_outData['paid'];
	    if (isset($this->_data['inv_status'])) {
			// This seems to be a thing of the past.
			// I think we could delete the whole "if".
			$this->_outData['status'] = $this->_data['inv_status'];
		}
		else {
			$this->_outData['status'] = '';
		}
		
		#invoice total tax
	    $result = $invoiceItems->getTotals($this->_id);
		
		//$invoice['total'] = number_format($result['total'],2);
		$this->_outData['total_tax'] = $result['total_tax'];
		
		$this->_outData['tax_grouped'] = taxesGroupedForInvoice($id);
	}
	
	
	/**
	 * Get the biller for this invoice.
	 * @return SimpleInvoices_Biller
	 */
	public function getBiller()
	{
		return new SimpleInvoices_Biller($this->_data['biller_id']);
	}
	
	/**
	 * Get the customer for this invoice.
	 * @return SimpleInvoices_Customer
	 */
	public function getCustomer()
	{
		return new SimpleInvoices_Customer($this->_data['customer_id']);
	}
	
	/**
	 * Get the current invoice identifier.
	 * @return int
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	public function getNumberOfTaxes()
	{
		$tbl_prefix = SimpleInvoices_Db_Table_Abstract::getTablePrefix();
		
		$select = new Zend_Db_Select($this->_db);
		$select->distinct(true);
		$select->from($tbl_prefix . 'tax', array('tax_id'));
		$select->joinInner($tbl_prefix . 'invoice_item_tax', $tbl_prefix . "invoice_item_tax.tax_id = " . $tbl_prefix . "tax.tax_id", NULL);
		$select->joinInner($tbl_prefix . "invoice_items", $tbl_prefix . "invoice_items.id = " . $tbl_prefix . "invoice_item_tax.invoice_item_id", NULL);
		$select->where($tbl_prefix . "invoice_items.invoice_id=?", $this->_id);
		$select->group(array($tbl_prefix . "tax.tax_id"));
		
		$result = $this->_db->fetchAll($select);
		return count($result);
	}
	
	/**
	 * Get payments for this invoice.
	 */
	public function getPayments()
	{
		$tbl_prefix = SimpleInvoices_Db_Table_Abstract::getTablePrefix();
		
		$select = new Zend_Db_Select($this->_db);
		$select->from($tbl_prefix . 'payment');
		$select->joinInner($tbl_prefix . 'invoices', $tbl_prefix. 'payment.ac_inv_id=' . $tbl_prefix . 'invoices.id', NULL);
		$select->joinInner($tbl_prefix . 'customers', $tbl_prefix . 'customers.id=' . $tbl_prefix . 'invoices.customer_id', array('cname' => $tbl_prefix . 'customers.name'));
		$select->joinInner($tbl_prefix . 'biller', $tbl_prefix . 'biller.id=' . $tbl_prefix . 'invoices.biller_id', array('bname' => $tbl_prefix. 'biller.name'));
		$select->where($tbl_prefix . 'payment.ac_inv_id=?', $this->_id);
		$select->order($tbl_prefix . 'payment.id DESC');
		
		return $this->_db->fetchAll($select);
	}
	
	public function getPreference()
	{
		$preferences = new SimpleInvoices_Db_Table_Preferences();
		return $preferences->getPreferenceById($this->_data['preference_id']);
	}
	
	public function getType()
	{
		$InvoiceTypes = new SimpleInvoices_Db_Table_InvoiceType();
		return $InvoiceTypes->getInvoiceType($this->_data['type_id']);
	}
	
	public function toArray()
	{
		$retData = array_merge($this->_data,$this->_outData);
		$retData['id'] = $this->_id;
		return $retData;
	}
}