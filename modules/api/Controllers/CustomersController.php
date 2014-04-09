<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class Customers{

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
	
	protected function CreateCustomerNodes(&$root_element,&$doc,$customer)
	{
		global $LANG;
		
		$id = $doc->createElement("id");
		$id->appendChild($doc->createTextNode($customer['id']));
		$root_element->appendChild($id);

		//$domain_id = $doc->createElement("domain_id");
		//$domain_id->appendChild($doc->createTextNode($customer['domain_id']));
		//$root_element->appendChild($domain_id);
		
		$attention = $doc->createElement("attention");
		$attention->appendChild($doc->createTextNode($customer['attention']));
		$root_element->appendChild($attention);
		
		$name = $doc->createElement("name");
		$name->appendChild($doc->createTextNode($customer['name']));
		$root_element->appendChild($name);
		
		$street_address = $doc->createElement("street_address");
		$street_address->appendChild($doc->createTextNode($customer['street_address']));
		$root_element->appendChild($street_address);
		
		$street_address2 = $doc->createElement("street_address2");
		$street_address2->appendChild($doc->createTextNode($customer['street_address2']));
		$root_element->appendChild($street_address2);
		
		$city = $doc->createElement("city");
		$city->appendChild($doc->createTextNode($customer['city']));
		$root_element->appendChild($city);
		
		$state = $doc->createElement("state");
		$state->appendChild($doc->createTextNode($customer['state']));
		$root_element->appendChild($state);
		
		$zip_code = $doc->createElement("zip_code");
		$zip_code->appendChild($doc->createTextNode($customer['zip_code']));
		$root_element->appendChild($zip_code);
		
		$country = $doc->createElement("country");
		$country->appendChild($doc->createTextNode($customer['country']));
		$root_element->appendChild($country);
		
		$phone = $doc->createElement("phone");
		$phone->appendChild($doc->createTextNode($customer['phone']));
		$root_element->appendChild($phone);
		
		$mobile_phone = $doc->createElement("mobile_phone");
		$mobile_phone->appendChild($doc->createTextNode($customer['mobile_phone']));
		$root_element->appendChild($mobile_phone);
		
		$fax = $doc->createElement("fax");
		$fax->appendChild($doc->createTextNode($customer['fax']));
		$root_element->appendChild($fax);
		
		$email = $doc->createElement("email");
		$email->appendChild($doc->createTextNode($customer['email']));
		$root_element->appendChild($email);
		
		$credit_card_holder_name = $doc->createElement("credit_card_holder_name");
		$credit_card_holder_name->appendChild($doc->createTextNode($customer['credit_card_holder_name']));
		$root_element->appendChild($credit_card_holder_name);
		
		$credit_card_number = $doc->createElement("credit_card_number");
		$credit_card_number->appendChild($doc->createTextNode($customer['credit_card_number']));
		$root_element->appendChild($credit_card_number);
		
		$credit_card_expiry_month = $doc->createElement("credit_card_expiry_month");
		$credit_card_expiry_month->appendChild($doc->createTextNode($customer['credit_card_expiry_month']));
		$root_element->appendChild($credit_card_expiry_month);
		
		$credit_card_expiry_year = $doc->createElement("credit_card_expiry_year");
		$credit_card_expiry_year->appendChild($doc->createTextNode($customer['credit_card_expiry_year']));
		$root_element->appendChild($credit_card_expiry_year);
		
		$notes = $doc->createElement("notes");
		$notes->appendChild($doc->createTextNode($customer['notes']));
		$root_element->appendChild($notes);
		
		$custom_field1 = $doc->createElement("custom_field1");
		$custom_field1->appendChild($doc->createTextNode($customer['custom_field1']));
		$root_element->appendChild($custom_field1);
		
		$custom_field2 = $doc->createElement("custom_field2");
		$custom_field2->appendChild($doc->createTextNode($customer['custom_field2']));
		$root_element->appendChild($custom_field2);
		
		$custom_field3 = $doc->createElement("custom_field3");
		$custom_field3->appendChild($doc->createTextNode($customer['custom_field3']));
		$root_element->appendChild($custom_field3);
		
		$custom_field4 = $doc->createElement("custom_field4");
		$custom_field4->appendChild($doc->createTextNode($customer['custom_field4']));
		$root_element->appendChild($custom_field4);
		
		$total = $doc->createElement("total");
		$total->appendChild($doc->createTextNode($customer['total']));
		$root_element->appendChild($total);
		
		$paid = $doc->createElement("paid");
		$paid->appendChild($doc->createTextNode($customer['paid']));
		$root_element->appendChild($paid);
		
		$owing = $doc->createElement("owing");
		$owing->appendChild($doc->createTextNode($customer['owing']));
		$root_element->appendChild($owing);
		
		if ($customer['enabled'] === $LANG['enabled']) 
		{
			$customer['enabled'] = 1;
		} 
		else if($customer['enabled'] === $LANG['disabled'])
		{
			$customer['enabled'] = 0;
		}
		
		$enabled = $doc->createElement("enabled");
		$enabled->appendChild($doc->createTextNode($customer['enabled']));
		$root_element->appendChild($enabled);
	}
	
	protected function CreatePostBody($customers)
	{
		$_POST[name]=(string)$customers->name;
		$_POST[attention]=(string)$customers->attention;
		$_POST[street_address]=(string)$customers->street_address;
		$_POST[street_address2]=(string)$customers->street_address2;
		$_POST[city]=(string)$customers->city;
		$_POST[state]=(string)$customers->state;
		$_POST[zip_code]=(string)$customers->zip_code;
		$_POST[country]=(string)$customers->country;
		$_POST[phone]=(string)$customers->phone;
		$_POST[mobile_phone]=(string)$customers->mobile_phone;
		$_POST[fax]=(string)$customers->fax;
		$_POST[email]=(string)$customers->email;
		$_POST[notes]=(string)$customers->notes;
		$_POST[credit_card_holder_name]=(string)$customers->credit_card_holder_name;
		$_POST[credit_card_number]=(string)$customers->credit_card_number;
		$_POST[credit_card_expiry_month]=(string)$customers->credit_card_expiry_month;
		$_POST[credit_card_expiry_year]=(string)$customers->credit_card_expiry_year;
		$_POST[custom_field1]=(string)$customers->custom_field1;
		$_POST[custom_field2]=(string)$customers->custom_field2;
		$_POST[custom_field3]=(string)$customers->custom_field3;
		$_POST[custom_field4]=(string)$customers->custom_field4;
		$_POST['enabled']=(string)$customers->enabled;
		
		//use mb_strlen instead of strlen because of cyrillic characters
		if (trim($_POST[name])=="" || mb_strlen($_POST[name])>255) 
		{
		    return "Inavlid length of customer name!";
		}
		if (mb_strlen($_POST[credit_card_expiry_month])>2)
		{
			return "Inavlid length of customer credit card expiry month!";
		}
		if (mb_strlen($_POST[credit_card_expiry_year])>4)
		{
			return "Inavlid length of customer credit card expiry year!";
		}
		if (!is_numeric($_POST['enabled']) || $_POST['enabled']>=2 || $_POST['enabled']<0)
		{
			return "Inavlid customer enabled field!";
		}
		
		return "";
	}
	
	protected function GetCustomer($ID)
	{
		$customer;
		try
		{
			$customer = getCustomer($ID);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		if(!isset($customer['id']))
		{
			//No resource at the specified URL
			header('HTTP/1.1 404 Not Found');
			exit();
		}
		else
		{
			#invoice total calc - start
			$customer['total'] = calc_customer_total($customer['id']);
			#invoice total calc - end

			#amount paid calc - start
			$customer['paid'] = calc_customer_paid($customer['id']);
			#amount paid calc - end

			#amount owing calc - start
			$customer['owing'] = $customer['total'] - $customer['paid'];
			#amount owing calc - end
		}

		$doc = new DOMDocument('1.0','UTF-8');
		$doc->formatOutput = true;
		
		$root_element = $doc->createElement("customer");
		$doc->appendChild($root_element);

		try
		{
			$this->CreateCustomerNodes($root_element,$doc,$customer);
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
	  
	  $root_element = $doc->createElement("customers");
	  $doc->appendChild($root_element);
	  
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
	  
	  $customers=array();
	  
	  try
	  {
			$customers=getCustomers();
	  }
	  catch (Exception $e){
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
	  }
	  
	  if(count($customers)<=0)
	  {
			//Successful request when no data is returned
			header('HTTP/1.1 204 No Content');
			exit();
	  }
	  
	  foreach ($customers as $customer)
	  {
	    $customerNode = $doc->createElement("customer");
		$customerNode = $root_element->appendChild($customerNode);
		try
		{
			$this->CreateCustomerNodes($customerNode,$doc,$customer);
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
		
		return $this->GetCustomer($this->_queryStr["id"]);
		
	}
     
	//Creates a new customer 
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
			$customers = new SimpleXMLElement($this->_rawBody);
			//foreach ($customers->customer as $customer) {
			   //var_export((string)$customers->name); //(string)$customer->domain_id
			//}
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($customers);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
			if (insertCustomer()) {
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
				return $this->GetCustomer($insertID);//$doc;
			}
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}

	}
	
	//Updates an existing customer
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
		
			$customers = new SimpleXMLElement($this->_rawBody);
			
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($customers);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
		    $_POST['credit_card_number_new'] = $_POST['credit_card_number'];
			if (updateCustomer()) {
				$saved = true;
				// saveCustomFieldValues($_POST['categorie'],lastInsertId());
				//Successful request when something is updated at another URL 
				header('HTTP/1.1 200 OK - Updated successfully');
				return $this->GetCustomer($this->_queryStr['id']);//exit();
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