<?php


class SimpleInvoices_Biller
{

	/**
	 * Database object.
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;
	
	/**
	 * Biller indentifier.
	 * @var int
	 */
	private $_id;
	
	/**
	 * Biller data.
	 * @var array
	 */
	private $_data;
	
	public function __construct($biller)
	{
		// ToDo: Pass configuration options
		$this->_db = Zend_Db_Table::getDefaultAdapter();
		$this->_id = $biller;
		
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
		$billers = new SimpleInvoices_Db_Table_Biller();
		$this->_data = $billers->getBiller($this->_id);
	}

	/**
	 * Do not know if this should go here.
	 * In a Zend framework application we could just use the router 
	 */
	protected function _getUrl()
	{
		// ToDo: Get rid of this global variable.
	    global $config;
	    $baseUrl = Zend_Registry::get('baseUrl');
	    
	    $port = "";
	    $dir = dirname($_SERVER['PHP_SELF']);
	    //remove incorrenct slashes for WinXP etc.
	    $dir = str_replace('\\','',$dir);
	
	    $dir = str_replace($baseUrl,'',$dir);
		// $dir = str_replace('//','',$dir);
	
	    //set the port of http(s) section
	    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
	        $_SERVER['FULL_URL'] = "https://";
	    } else {
	        $_SERVER['FULL_URL'] = "http://";
	
	    }
	
	    $_SERVER['FULL_URL'] .= $config->authentication->http.$_SERVER['HTTP_HOST'].$dir;
	
	    return $_SERVER['FULL_URL'];
	}
	  
	/**
	 * Get the biller logo.
	 * @return string A string representing the path to the biller's logo.
	 */
	public function getLogo()
	{
		$url = $this->_getUrl();
	
		if(!empty($this->_data['logo'])) {
			return $url."/images/logos/$this->_data[logo]";
		}
		else {
			return $url."/images/logos/_default_blank_logo.png";
		}	
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