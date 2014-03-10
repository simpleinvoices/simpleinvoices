<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class Payments{

    protected $_request;
	protected $_queryStr;
	protected $_rawBody;
	protected $_method;
	
	public function __construct() {
		// i am using ZEND 1.11 
		// if ZEND 2, you should use Zend\Http\Request
		// use this Zend Request Class as it does 
		// most of the hard work for a web Request 
		$this->_request = new Zend_Controller_Request_Http();
		
		$this->_method = $this->_request->getMethod();
		
		$func = strtolower(trim(str_replace("/","",$this->_method)));
		if((int)method_exists($this,$func) <= 0) 
		{
		    //when a client makes a request using an unknown HTTP verb
			header('HTTP/1.1 501 Not Implemented');
			exit();
		}
		
		$this->_queryStr = $this->_request->getQuery();
		$this->_rawBody = $this->_request->getRawBody();

	}
	
	protected function CreateItemNodes(&$root_element,&$doc,$payment)
	{
		global $LANG;

		$id = $doc->createElement("id");
		$id->appendChild($doc->createTextNode($payment['id']));
		$root_element->appendChild($id);
		
		$invoice_id = $doc->createElement("invoice_id");
		$invoice_id->appendChild($doc->createTextNode($payment['ac_inv_id']));
		$root_element->appendChild($invoice_id);
		
		$biller_id = $doc->createElement("biller_id");
		$biller_id->appendChild($doc->createTextNode($payment['biller_id']));
		$root_element->appendChild($biller_id);
		if (!is_numeric($payment['biller_id']))
		{
			$biller_id->setAttribute("xsi:nil", "true");
		}
		
		$biller_name = $doc->createElement("biller_name");
		$biller_name->appendChild($doc->createTextNode($payment['biller']));
		$root_element->appendChild($biller_name);
		
		$customer_id = $doc->createElement("customer_id");
		$customer_id->appendChild($doc->createTextNode($payment['customer_id']));
		$root_element->appendChild($customer_id);
		if (!is_numeric($payment['customer_id']))
		{
			$customer_id->setAttribute("xsi:nil", "true");
		}
		
		$customer_name = $doc->createElement("customer_name");
		$customer_name->appendChild($doc->createTextNode($payment['customer']));
		$root_element->appendChild($customer_name);
		
		$amount = $doc->createElement("amount");
		$amount->appendChild($doc->createTextNode($payment['ac_amount']));
		$root_element->appendChild($amount);
		
		$notes = $doc->createElement("notes");
		$notes->appendChild($doc->createTextNode($payment['ac_notes']));
		$root_element->appendChild($notes);
		
		$date = $doc->createElement("date");
		$date->appendChild($doc->createTextNode( date('Y-m-d H:i:s', strtotime( $payment['ac_date'] ) )));
		$root_element->appendChild($date);
		
		$payment_type_id = $doc->createElement("payment_type_id");
		$payment_type_id->appendChild($doc->createTextNode($payment['ac_payment_type']));
		$root_element->appendChild($payment_type_id);
		
		$payment_type_name = $doc->createElement("payment_type_name");
		$payment_type_name->appendChild($doc->createTextNode($payment['payment_type_name']));
		$root_element->appendChild($payment_type_name);
		
		$index_name = $doc->createElement("index_name");
		$index_name->appendChild($doc->createTextNode($payment['index_name']));
		$root_element->appendChild($index_name);
		
	}
	
	protected function CreatePostBody($payments)
	{
		$_POST[invoice_id]=(string)$payments->invoice_id;
		$_POST[ac_amount]=(string)$payments->amount;
		$_POST[ac_notes]=(string)$payments->notes;
		$_POST[ac_date]=(string)$payments->date;
		$_POST[ac_payment_type]=(string)$payments->payment_type_id;
		
	
		if (!is_numeric($_POST[invoice_id])|| $_POST[invoice_id]<=0)
		{
			return "Not valid invoice_id field!";
		}
		if (!is_numeric($_POST[ac_amount]))
		{
			return "Not numeric amount field!";
		}
		if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $_POST[ac_amount]))
		{
			 return "Inavlid number format for amount!" ;
		}
		if (!is_numeric($_POST[ac_payment_type])|| $_POST[ac_payment_type]<=0)
		{
			return "Not valid payment_type_id field!";
		}
		
		//valid date in format YYYY-MM-DD H:i:s
		$d = DateTime::createFromFormat('Y-m-d H:i:s', $_POST[ac_date]);
        if($d && $d->format('Y-m-d H:i:s') == $_POST[ac_date])
		{
		   //var_export( $_POST[date]);
		}
		else
		{
			return "Invalid date - must be in format YYYY-MM-DD H:i:s";
		}
		
		
		return "";
	}
	
	protected function getPayments($PaymentID='')
	{
	    global $auth_session;
		global $dbh;
		
		$where = "";
	    if ($PaymentID!='') 
		{
		  $where .= " AND ap.id = " .$PaymentID;
		}

		$sql = "SELECT 
				ap.*, 
				c.id as customer_id, 
				c.name AS customer, 
				b.id as biller_id, 
				b.name AS biller, 
				pt.pt_description AS payment_type_name,
				ac_notes AS notes,
				(SELECT CONCAT(p.pref_inv_wording,' ',iv.index_id)) as index_name,
				DATE_FORMAT(ac_date,'%Y-%m-%d') AS date
		FROM 
			".TB_PREFIX."payment ap, 
			".TB_PREFIX."invoices iv, 
			".TB_PREFIX."customers c, 
			".TB_PREFIX."biller b ,
			".TB_PREFIX."preferences p,
			".TB_PREFIX."payment_types pt 
		WHERE 
			ap.domain_id = :domain_id
		AND ap.ac_inv_id = iv.id 
		AND iv.domain_id = ap.domain_id
		AND iv.customer_id = c.id 
		AND c.domain_id = iv.domain_id
		AND iv.biller_id = b.id 
		AND b.domain_id = iv.domain_id
		AND iv.preference_id = p.pref_id
		AND p.domain_id = ap.domain_id
		AND ap.ac_payment_type = pt.pt_id 
		AND pt.domain_id = ap.domain_id
		    $where
		ORDER BY 
			ap.id DESC";
					
				
		$result =  dbQuery($sql,':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));

		if ($PaymentID!='') 
		{
		   $payments = $result->fetch();
		}
		else
		{
		   $payments = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		
		return $payments;
	}
	
	protected function GetPayment($ID)
	{
		$payment;
		try
		{
			$payment = $this->getPayments($ID); //getPayment($this->_queryStr["id"]);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		if(!isset($payment['id']))
		{
			//No resource at the specified URL
			header('HTTP/1.1 404 Not Found');
			exit();
		}

		$doc = new DOMDocument('1.0','UTF-8');
		$doc->formatOutput = true;
		
		$root_element = $doc->createElement("payment");
		$doc->appendChild($root_element);
		$root_element->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
	    $root_element->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		try
		{
			$this->CreateItemNodes($root_element,$doc,$payment);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		//$xml = $doc->saveXML();
		//$xml = simplexml_load_string($xml);
		
		return $doc;//$xml;
	}
	
	public  function index()
	{
	  $doc = new DOMDocument('1.0','UTF-8');
	  $doc->formatOutput = true;
	  
	  $root_element = $doc->createElement("payments");
	  $doc->appendChild($root_element);
	  $root_element->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
	  $root_element->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	  
	  if($this->_method!="GET")
	  {     
	        //when a client makes a request using an HTTP verb not supported at the requested URL 
			//(supported verbs are returned in the Allow header)
			header('HTTP/1.1 405 Method Not Allowed');
			exit();
	  }
	  if(isset($this->_queryStr['id']))
	  {
	        //Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request');
			exit();
	  }
	  
	  $payments=array();
	  
	  try
	  {
			$payments=$this->getPayments();
	  }
	  catch (Exception $e){
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
	  }
	  
	  if(count($payments)<=0)
	  {
			//Successful request when no data is returned
			header('HTTP/1.1 204 No Content');
			exit();
	  }
	  
	  foreach ($payments as $payment)
	  {
	    $itemNode = $doc->createElement("payment");
		$itemNode = $root_element->appendChild($itemNode);
		try
		{
			$this->CreateItemNodes($itemNode,$doc,$payment);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
	  }
	
	  return $doc;
	  //var_export($doc->saveXML(), true);
	}
	
	public function get()
	{
		// we will dump the query_string
		// with a message from GET METHOD
		// if we get a get call
		// $this->_request->getQuery()
		// will return the $_SERVER['QUERY_STRING'] value
		
		if($this->_method!="GET")
		{     
			//when a client makes a request using an HTTP verb not supported at the requested URL 
			//(supported verbs are returned in the Allow header)
			header('HTTP/1.1 405 Method Not Allowed');
			exit();
		}
		if(!isset($this->_queryStr) || !isset($this->_queryStr["id"]) || !is_numeric($this->_queryStr["id"]))
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request');
			exit();
		}
		return $this->GetPayment($this->_queryStr["id"]);
	}
     
	//Creates a new payment 
	public function post()
	{
	    //global $dbh;
		// we will dump the POST DATA
		// with a message from POST METHOD
		// if we get a POST call
		// $this->_request->getRawBody()
		// will return the $_POST DATA
		
		if($this->_method!="POST")
		{     
			//when a client makes a request using an HTTP verb not supported at the requested URL 
			//(supported verbs are returned in the Allow header)
			header('HTTP/1.1 405 Method Not Allowed');
			exit();
		}
		if(isset($this->_queryStr['id'])|| $this->_rawBody==false )
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request');
			exit();
		}
		
        $saved=false;
		
		try
		{
			$payments = new SimpleXMLElement($this->_rawBody);
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($payments);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{   
		    global $db;
			
			$payment = new payment();
			$payment->ac_inv_id 		= $_POST['invoice_id'];
			$payment->ac_amount 		= $_POST['ac_amount'];
			$payment->ac_notes			= $_POST['ac_notes'];
			$payment->ac_date			= SqlDateWithTime($_POST['ac_date']);
			$payment->ac_payment_type	= $_POST['ac_payment_type'];
			$result = $payment->insert();
			
			$saved = !empty($result) ? "true" : "false";
			
			if ($saved) {
				$insertID =$db->lastInsertId();
				
				if ($insertID<=0)
				{
					//An unexpected error occurred
					header('HTTP/1.1 500 Internal Server Error');
					exit();
				}
				
				// $doc = new DOMDocument('1.0','UTF-8');
				// $doc->formatOutput = true;
				// $root_element = $doc->createElement("payment");
				// $doc->appendChild($root_element);
				// $id = $doc->createElement("id");
				// $id->appendChild($doc->createTextNode($insertID));
				// $root_element->appendChild($id);
				
				//Successful request when something is created at another URL 
				header('HTTP/1.1 201 Created');
				return $this->GetPayment($insertID); //$doc;
			}
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}

	}
	
	//Updates 
	/*public function put()
	{
		
	}
	
	public  function delete()
	{
			$data = $this->_request->getRawBody();	
            return "FROM DELETE METHOD.\n" . 
			var_export($data, true);
	}
	
    */
}