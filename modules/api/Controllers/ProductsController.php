<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Products{

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
	
	protected function CreateItemNodes(&$root_element,&$doc,$product)
	{
		global $LANG;
	
		$id = $doc->createElement("id");
		$id->appendChild($doc->createTextNode($product['id']));
		$root_element->appendChild($id);

		//$domain_id = $doc->createElement("domain_id");
		//$domain_id->appendChild($doc->createTextNode($product['domain_id']));
		//$root_element->appendChild($domain_id);
		
		$description = $doc->createElement("description");
		$description->appendChild($doc->createTextNode($product['description']));
		$root_element->appendChild($description);
		
		$unit_price = $doc->createElement("unit_price");
		$unit_price->appendChild($doc->createTextNode($product['unit_price']));
		$root_element->appendChild($unit_price);
		if (!is_numeric($product['unit_price']))
		{
			$unit_price->setAttribute("xsi:nil", "true");
		}
		
		$default_tax_id = $doc->createElement("default_tax_id");
		$default_tax_id->appendChild($doc->createTextNode($product['default_tax_id']));
		$root_element->appendChild($default_tax_id);
		if (!is_numeric($product['default_tax_id']))
		{
			$default_tax_id->setAttribute("xsi:nil", "true");
		}
		
		$default_tax_id_2 = $doc->createElement("default_tax_id_2");
		$default_tax_id_2->appendChild($doc->createTextNode($product['default_tax_id_2']));
		$root_element->appendChild($default_tax_id_2);
		if (!is_numeric($product['default_tax_id_2']))
		{
			$default_tax_id_2->setAttribute("xsi:nil", "true");
		}
		
		$cost = $doc->createElement("cost");
		$cost->appendChild($doc->createTextNode($product['cost']));
		$root_element->appendChild($cost);
		if (!is_numeric($product['cost']))
		{
			$cost->setAttribute("xsi:nil", "true");
		}
		
		$reorder_level = $doc->createElement("reorder_level");
		$reorder_level->appendChild($doc->createTextNode($product['reorder_level']));
		$root_element->appendChild($reorder_level);
		if (!is_numeric($product['reorder_level']))
		{
			$reorder_level->setAttribute("xsi:nil", "true");
		}

		$notes = $doc->createElement("notes");
		$notes->appendChild($doc->createTextNode($product['notes']));
		$root_element->appendChild($notes);
		
		$custom_field1 = $doc->createElement("custom_field1");
		$custom_field1->appendChild($doc->createTextNode($product['custom_field1']));
		$root_element->appendChild($custom_field1);
		
		$custom_field2 = $doc->createElement("custom_field2");
		$custom_field2->appendChild($doc->createTextNode($product['custom_field2']));
		$root_element->appendChild($custom_field2);
		
		$custom_field3 = $doc->createElement("custom_field3");
		$custom_field3->appendChild($doc->createTextNode($product['custom_field3']));
		$root_element->appendChild($custom_field3);
		
		$custom_field4 = $doc->createElement("custom_field4");
		$custom_field4->appendChild($doc->createTextNode($product['custom_field4']));
		$root_element->appendChild($custom_field4);

		
		if ($product['enabled'] === $LANG['enabled']) 
		{
			$product['enabled'] = 1;
		} 
		else if($product['enabled'] === $LANG['disabled'])
		{
			$product['enabled'] = 0;
		}
		
		$enabled = $doc->createElement("enabled");
		$enabled->appendChild($doc->createTextNode($product['enabled']));
		$root_element->appendChild($enabled);
		
		$visible = $doc->createElement("visible");
		$visible->appendChild($doc->createTextNode($product['visible']));
		$root_element->appendChild($visible);
		
		
		//attributes
		$attributes = json_decode($product['attribute'],true);
		foreach ($attributes as $key => $value)
		{
			$attribute_id = $doc->createElement("attribute_id");
			$attribute_id->appendChild($doc->createTextNode($key));
			$root_element->appendChild($attribute_id);
			if (!is_numeric($key))
			{
				$attribute_id->setAttribute("xsi:nil", "true");
			}
		}
		
		$nsDesc;
		if($product['notes_as_description']=='Y')
		{
			$nsDesc='1';
		}
		else
		{
			$nsDesc='0';
		}
		$notes_as_description = $doc->createElement("notes_as_description");
		$notes_as_description->appendChild($doc->createTextNode($nsDesc));
		$root_element->appendChild($notes_as_description);
		
		if($product['show_description']=='Y')
		{
			$nsDesc='1';
		}
		else
		{
			$nsDesc='0';
		}
		$show_description = $doc->createElement("show_description");
		$show_description->appendChild($doc->createTextNode($nsDesc));
		$root_element->appendChild($show_description);
	}
	
	protected function CreatePostBody($products)
	{
		$_POST[description]=(string)$products->description;
		$_POST[unit_price]=(string)$products->unit_price;
		$_POST[default_tax_id]=(string)$products->default_tax_id;
		$_POST[default_tax_id_2]=(string)$products->default_tax_id_2;
		$_POST[cost]=(string)$products->cost;
		$_POST[reorder_level]=(string)$products->reorder_level;
		$_POST[notes]=(string)$products->notes;
		$_POST[custom_field1]=(string)$products->custom_field1;
		$_POST[custom_field2]=(string)$products->custom_field2;
		$_POST[custom_field3]=(string)$products->custom_field3;
		$_POST[custom_field4]=(string)$products->custom_field4;
		$_POST[enabled]=(string)$products->enabled;
		$_POST[visible]=(string)$products->visible;
		$_POST[notes_as_description]=(string)$products->notes_as_description;
		$_POST[show_description]=(string)$products->show_description;
		
		//attributes
		foreach($products->attribute_id as $attribute)
		{
			$attributeID =(string)$attribute;
			if (trim($attributeID)=="" || !is_numeric($attributeID) || $attributeID<=0)
			{
				return "Not valid attribute_id of the product!" ;
			}
			$_POST['attribute'.$attributeID] = 'true';
		}

		if (trim($_POST[description])=="")
		{
		    return "Inavlid length of description!";
		}
		if (!is_numeric($_POST[unit_price]))
		{
			return "Not numeric unit_price field!";
		}
		if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $_POST[unit_price]))
		{
			 return "Inavlid number format for unit_price!" ;
		}
		
		if (!is_numeric($_POST[default_tax_id])|| $_POST[default_tax_id]<=0)
		{
			return "Not valid default_tax_id field!";
		}
		
		if (trim($_POST[cost])=="")
		{
			$_POST[cost]=NULL;
		}
		else if (!is_numeric($_POST[cost]))
		{
			return "Not numeric cost field!";
		}
		else if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $_POST[cost]))
		{
			 return "Inavlid number format for cost!" ;
		}
		
		if(trim($_POST[reorder_level])=="")
		{
			$_POST[reorder_level]=NULL;
		}
		else if (!is_numeric($_POST[reorder_level]))
		{
			return "Not numeric reorder_level field!";
		}
		
		if(trim($_POST[default_tax_id_2])=="")
		{
			$_POST[default_tax_id_2]=NULL;
		}
		else if (!is_numeric($_POST[default_tax_id_2]))
		{
			return "Not numeric default_tax_id_2 field!";
		}

		if (!is_numeric($_POST[enabled]) || $_POST[enabled]>=2 || $_POST[enabled]<0)
		{
			return "Inavlid enabled field!";
		}
		if (!is_numeric($_POST[visible]) || $_POST[visible]>=2 || $_POST[visible]<0)
		{
			return "Inavlid visible field!";
		}
		if (!is_numeric($_POST[notes_as_description]) || $_POST[notes_as_description]>=2 || $_POST[notes_as_description]<0)
		{
			return "Inavlid notes_as_description field - only 0/1!";
		}
		if (!is_numeric($_POST[show_description]) || $_POST[show_description]>=2 || $_POST[show_description]<0)
		{
			return "Inavlid show_description field - only 0/1!";
		}
		
		if ($_POST[notes_as_description]=='1')
		{
		   $_POST[notes_as_description]='true';
		}
		if ($_POST[show_description]=='1')
		{
		   $_POST[show_description]='true';
		}
		
		return "";
	}
	
	protected function GetProduct($ID)
	{
		$product;
		try
		{
			$product = getProduct($ID);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		if(!isset($product['id']))
		{
			//No resource at the specified URL
			header('HTTP/1.1 404 Not Found');
			exit();
		}

		$doc = new DOMDocument('1.0','UTF-8');
		$doc->formatOutput = true;
		
		$root_element = $doc->createElement("product");
		$doc->appendChild($root_element);
		$root_element->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $root_element->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		try
		{
			$this->CreateItemNodes($root_element,$doc,$product);
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
	  
	  $root_element = $doc->createElement("products");
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
	  
	  $products=array();
	  
	  try{
			$products=getProducts();
	  }
	  catch (Exception $e){
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
	  }
	  
	  if(count($products)<=0)
	  {
			//Successful request when no data is returned
			header('HTTP/1.1 204 No Content');
			exit();
	  }
	  
	  foreach ($products as $product)
	  {
	    $itemNode = $doc->createElement("product");
		$itemNode = $root_element->appendChild($itemNode);
		try
		{
			$this->CreateItemNodes($itemNode,$doc,$product);
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
		return $this->GetProduct($this->_queryStr["id"]);
	}
     
	//Creates a new product 
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
			$products = new SimpleXMLElement($this->_rawBody);
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($products);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
			if (insertProduct()) {
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
				// $root_element = $doc->createElement("product");
				// $doc->appendChild($root_element);
				// $id = $doc->createElement("id");
				// $id->appendChild($doc->createTextNode($insertID));
				// $root_element->appendChild($id);
				
				//Successful request when something is created at another URL 
				header('HTTP/1.1 201 Created');
				return $this->GetProduct($insertID); //$doc;
			}
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}

	}
	
	//Updates an existing product
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
		
			$products = new SimpleXMLElement($this->_rawBody);
			
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($products);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
			if (updateProduct()) {
				$saved = true;
				//Successful request when something is updated at another URL 
				header('HTTP/1.1 200 OK - Updated successfully');
				return $this->GetProduct($this->_queryStr["id"]);//exit();
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