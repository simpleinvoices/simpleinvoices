<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class TaxRates{

    protected $_request;
	protected $_queryStr;
	protected $_rawBody;
	protected $_method;
	protected $_domain_id;
	
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
		$this->_domain_id = domain_id::get('');

	}
	
	protected function CreateItemNodes(&$root_element,&$doc,$taxRate)
	{
		global $LANG;
		
		$id = $doc->createElement("tax_id");
		$id->appendChild($doc->createTextNode($taxRate['tax_id']));
		$root_element->appendChild($id);
		
		$description = $doc->createElement("tax_description");
		$description->appendChild($doc->createTextNode($taxRate['tax_description']));
		$root_element->appendChild($description);
		
		$percentage = $doc->createElement("tax_percentage");
		$percentage->appendChild($doc->createTextNode($taxRate['tax_percentage']));
		$root_element->appendChild($percentage);
		if (!is_numeric($taxRate['tax_percentage']))
		{
			$percentage->setAttribute("xsi:nil", "true");
		}

		$type = $doc->createElement("type");
		$type->appendChild($doc->createTextNode($taxRate['type']));
		$root_element->appendChild($type);
		
		if ($taxRate['enabled'] === $LANG['enabled']) 
		{
			$taxRate['enabled'] = 1;
		} 
		else if($taxRate['enabled'] === $LANG['disabled'])
		{
			$taxRate['enabled'] = 0;
		}
		
		$enabled = $doc->createElement("enabled");
		$enabled->appendChild($doc->createTextNode($taxRate['enabled']));
		$root_element->appendChild($enabled);
	}
	
	protected function CreatePostBody($taxRates)
	{
		$_POST[tax_description]=(string)$taxRates->tax_description;
		$_POST[tax_percentage]=(string)$taxRates->tax_percentage;
		$_POST[type]=(string)$taxRates->type;
		$_POST[tax_enabled]=(string)$taxRates->enabled;
		
		//use mb_strlen instead of strlen because of cyrillic characters
		if (trim($_POST[tax_description])=="" || mb_strlen($_POST[tax_description])>50) 
		{
		    return "Inavlid length of tax_description!";
		}
		if (!is_numeric($_POST[tax_percentage]))
		{
			return "Not numeric tax_percentage field!";
		}
		if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $_POST[tax_percentage]))
		{
			 return "Inavlid number format for tax_percentage!" ;
		}
		if(trim($_POST[type])=="" || mb_strlen($_POST[type])>1)
		{
			return "Inavlid length of type!";
		}
		if (!is_numeric($_POST[tax_enabled]) || $_POST[tax_enabled]>=2 || $_POST[tax_enabled]<0)
		{
			return "Inavlid tax_enabled field!";
		}
		
		return "";
	}
	
	protected function GetTaxRate($ID)
	{
		$taxRate;
		try
		{
			$taxRate = getTaxRate($ID);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		if(!isset($taxRate['tax_id']))
		{
			//No resource at the specified URL
			header('HTTP/1.1 404 Not Found');
			exit();
		}
		
		$doc = new DOMDocument('1.0','UTF-8');
		$doc->formatOutput = true;
		
		$root_element = $doc->createElement("taxRate");
		$doc->appendChild($root_element);
		//necessary when send "xsi:nil"="true" attributes
		$root_element->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
	    $root_element->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		try
		{
			$this->CreateItemNodes($root_element,$doc,$taxRate);
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
	  
	  $root_element = $doc->createElement("taxRates");
	  $doc->appendChild($root_element);
	  //necessary when send "xsi:nil"="true" attributes
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
	  
	  $taxRates=array();
	  
	  try
	  {
	        $sql = "SELECT 
					tax_id, 
					tax_description,
					tax_percentage,
					type,
					(SELECT (CASE  WHEN tax_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
				    FROM 
					".TB_PREFIX."tax WHERE domain_id = :domain_id ORDER BY tax_description";
			$sth = dbQuery($sql,':domain_id', $this->_domain_id)or die(end($dbh->errorInfo()));
			$taxRates =$sth->fetchAll();
	  }
	  catch (Exception $e){
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
	  }
	  
	  if(count($taxRates)<=0)
	  {
			//Successful request when no data is returned
			header('HTTP/1.1 204 No Content');
			exit();
	  }
	  
	  foreach ($taxRates as $taxRate)
	  {
	    $itemNode = $doc->createElement("taxRate");
		$itemNode = $root_element->appendChild($itemNode);
		try
		{
			$this->CreateItemNodes($itemNode,$doc,$taxRate);
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
		
		return $this->GetTaxRate($this->_queryStr["id"]);
		
	}
     
	//Creates a new item
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
			//$xml = simplexml_load_string($data);
			$taxRates = new SimpleXMLElement($this->_rawBody);
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($taxRates);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
			if (insertTaxRate()) {
				$insertID = lastInsertId(); //$dbh->
				
				if ($insertID<=0)
				{
					//An unexpected error occurred
					header('HTTP/1.1 500 Internal Server Error');
					exit();
				}
				
				$saved = true;
				// $doc = new DOMDocument('1.0','UTF-8');
				// $doc->formatOutput = true;
				// $root_element = $doc->createElement("customer");
				// $doc->appendChild($root_element);
				// $id = $doc->createElement("id");
				// $id->appendChild($doc->createTextNode($insertID));
				// $root_element->appendChild($id);
				
				//Successful request when something is created at another URL 
				header('HTTP/1.1 201 Created');
				return $this->GetTaxRate($insertID);//$doc;
			}
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}

	}
	
	//Updates an existing item
	public function put()
	{
		if($this->_method!="PUT")
		{     
			//when a client makes a request using an HTTP verb not supported at the requested URL 
			//(supported verbs are returned in the Allow header)
			header('HTTP/1.1 405 Method Not Allowed');
			exit();
		}
		if(!isset($this->_queryStr['id']) || !is_numeric($this->_queryStr["id"])|| $this->_rawBody==false )
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request');
			exit();
		}
	
	    $saved=false;
		
		try
		{
		
			$taxRates = new SimpleXMLElement($this->_rawBody);
			
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($taxRates);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
			if (updateTaxRate()) {
				$saved = true;
				// saveCustomFieldValues($_POST['categorie'],lastInsertId());
				//Successful request when something is updated at another URL 
				header('HTTP/1.1 200 OK - Updated successfully');
				return $this->GetTaxRate($this->_queryStr['id']);//exit();
			}
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
	}
	
	/*public  function delete()
	{
			$data = $this->_request->getRawBody();	
            return "FROM DELETE METHOD.\n" . 
			var_export($data, true);
	}
	
    */
}