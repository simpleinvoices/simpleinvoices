<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
 /* TODO : Maria - in .htaccess - in rules substitute "-" with "/"
 in config.php insert apikey
 isvalid($_SERVER["REQUEST_URI"]);
 Ask about how and where to check whether url exists
 Whether to check the lenght/type of the inserted/updated fields
 or it is enough just to send code 500 internal server error
 Ask about Content-Type - whether to exists - xml/jason
 To change file library\encryption.php - delete "?>" at the end of the file 
 sql_queries - in updateCustomer line 1511 - change $dbQquery with dbQuery
 To change sql_queries updateInvoiceItem - $domain_id //Changed by Maria $auth_session->domain_id
 To change update product in save.php in modules/invoices - $domain_id //Changed by Maria $auth_session->domain_id
 Ask about - when update invoice - delete all items and also from invoice_tax_items and insert them again or update the item
 finally if there are not items saved then delete invoice - ask about that;
 !invoice_tax_items - delete when delete item?
 !product - to get it in xml?
 */

class Invoices{

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
	
	protected function CreateItemNodes(&$root_element,&$doc,$invoice)
	{
		global $LANG;
	
		$id = $doc->createElement("id");
		$id->appendChild($doc->createTextNode($invoice['id']));
		$root_element->appendChild($id);

		//$domain_id = $doc->createElement("domain_id");
		//$domain_id->appendChild($doc->createTextNode($invoice['domain_id']));
		//$root_element->appendChild($domain_id);
		
		$index_id = $doc->createElement("index_id");
		$index_id->appendChild($doc->createTextNode($invoice['index_id']));
		$root_element->appendChild($index_id);
		
		$biller_id = $doc->createElement("biller_id");
		$biller_id->appendChild($doc->createTextNode($invoice['biller_id']));
		$root_element->appendChild($biller_id);
		
		$customer_id = $doc->createElement("customer_id");
		$customer_id->appendChild($doc->createTextNode($invoice['customer_id']));
		$root_element->appendChild($customer_id);
		
		$type_id = $doc->createElement("type_id");
		$type_id->appendChild($doc->createTextNode($invoice['type_id']));
		$root_element->appendChild($type_id);
		
		$preference_id = $doc->createElement("preference_id");
		$preference_id->appendChild($doc->createTextNode($invoice['preference_id']));
		$root_element->appendChild($preference_id);
		
		$date = $doc->createElement("date");
		$date->appendChild($doc->createTextNode(date('Y-m-d', strtotime( $invoice['date'] ) )));
		$root_element->appendChild($date);

		$note = $doc->createElement("note");
		$note->appendChild($doc->createTextNode($invoice['note']));
		$root_element->appendChild($note);
		
		$custom_field1 = $doc->createElement("custom_field1");
		$custom_field1->appendChild($doc->createTextNode($invoice['custom_field1']));
		$root_element->appendChild($custom_field1);
		
		$custom_field2 = $doc->createElement("custom_field2");
		$custom_field2->appendChild($doc->createTextNode($invoice['custom_field2']));
		$root_element->appendChild($custom_field2);
		
		$custom_field3 = $doc->createElement("custom_field3");
		$custom_field3->appendChild($doc->createTextNode($invoice['custom_field3']));
		$root_element->appendChild($custom_field3);
		
		$custom_field4 = $doc->createElement("custom_field4");
		$custom_field4->appendChild($doc->createTextNode($invoice['custom_field4']));
		$root_element->appendChild($custom_field4);

	}
	
	protected function CreateInvoiceItemNodes(&$root_element,&$doc,$invoiceItem)
	{
		global $LANG;
	
		$invoice_item_id = $doc->createElement("invoice_item_id");
		$invoice_item_id->appendChild($doc->createTextNode($invoiceItem['id']));
		$root_element->appendChild($invoice_item_id);

		//$domain_id = $doc->createElement("domain_id");
		//$domain_id->appendChild($doc->createTextNode($invoiceItem['domain_id']));
		//$root_element->appendChild($domain_id);
		
		$invoice_id = $doc->createElement("invoice_id");
		$invoice_id->appendChild($doc->createTextNode($invoiceItem['invoice_id']));
		$root_element->appendChild($invoice_id);
		
		$quantity = $doc->createElement("quantity");
		$quantity->appendChild($doc->createTextNode($invoiceItem['quantity']));
		$root_element->appendChild($quantity);
		
		$product_id = $doc->createElement("product_id");
		$product_id->appendChild($doc->createTextNode($invoiceItem['product_id']));
		$root_element->appendChild($product_id);
		if (!is_numeric($invoiceItem['product_id']))
		{
			$product_id->setAttribute("xsi:nil", "true");
		}
		
		$unit_price = $doc->createElement("unit_price");
		$unit_price->appendChild($doc->createTextNode($invoiceItem['unit_price']));
		$root_element->appendChild($unit_price);
		if (!is_numeric($invoiceItem['unit_price']))
		{
			$unit_price->setAttribute("xsi:nil", "true");
		}
		
		$tax_amount = $doc->createElement("tax_amount");
		$tax_amount->appendChild($doc->createTextNode($invoiceItem['tax_amount']));
		$root_element->appendChild($tax_amount);
		if (!is_numeric($invoiceItem['tax_amount']))
		{
			$tax_amount->setAttribute("xsi:nil", "true");
		}
		
		$gross_total = $doc->createElement("gross_total");
		$gross_total->appendChild($doc->createTextNode($invoiceItem['gross_total']));
		$root_element->appendChild($gross_total);
		if (!is_numeric($invoiceItem['gross_total']))
		{
			$gross_total->setAttribute("xsi:nil", "true");
		}

		$description = $doc->createElement("description");
		$description->appendChild($doc->createTextNode($invoiceItem['description']));
		$root_element->appendChild($description);
		
		$total = $doc->createElement("total");
		$total->appendChild($doc->createTextNode($invoiceItem['total']));
		$root_element->appendChild($total);
		if (!is_numeric($invoiceItem['total']))
		{
			$total->setAttribute("xsi:nil", "true");
		}
		
		foreach($invoiceItem['tax'] as $tax) //in xml <tax_id>1</tax_id> few times
		{
			$tax_id = $doc->createElement("tax_id");
			$tax_id->appendChild($doc->createTextNode($tax));
			$root_element->appendChild($tax_id);
			if (!is_numeric($tax))
			{
				$tax_id->setAttribute("xsi:nil", "true");
			}
		}
		
		//attributes -start
		$attributes = $doc->createElement("attributes");
		$attributes = $root_element->appendChild($attributes);
		
		foreach ($invoiceItem['attribute_decode'] as $key => $value)
		{
			$attribute = $doc->createElement("attribute");
			$attribute = $attributes->appendChild($attribute);
			
			$prod_attribute_id = $doc->createElement("prod_attribute_id");
			$prod_attribute_id->appendChild($doc->createTextNode($key));
			$attribute->appendChild($prod_attribute_id);
			if (!is_numeric($key))
			{
				$prod_attribute_id->setAttribute("xsi:nil", "true");
			}
			
			$prod_value_id = $doc->createElement("prod_value_id");
			$prod_value_id->appendChild($doc->createTextNode($value));
			$attribute->appendChild($prod_value_id);
			if (!is_numeric($value))
			{
				$prod_value_id->setAttribute("xsi:nil", "true");
			}
			
		}
		//$attribute = $doc->createElement("attribute");
		//$attribute->appendChild($doc->createTextNode($invoiceItem['attribute']));
		//$root_element->appendChild($attribute);
		//attributes -end
	
	}
	
	protected function CreatePostBody($invoices,$isInsert)
	{
		$_POST[index_id]=(string)$invoices->index_id;
	
		$sql = "SELECT b.id AS id FROM ".TB_PREFIX."biller b, ".TB_PREFIX."system_defaults s WHERE ( s.name = 'biller' AND b.id = s.value AND b.domain_id = s.domain_id AND s.domain_id = :domain_id)";
		$sth = dbQuery($sql,':domain_id', $this->_domain_id);
	    $defBiller =$sth->fetch();
		
		//if ($isInsert)
		//{
			$_POST[biller_id]=$defBiller['id'];
		//}
		//else
		//{
		//	$_POST[biller_id]=(string)$invoices->biller_id;
		//}
		
		$_POST[customer_id]=(string)$invoices->customer_id;
		$_POST[type_id]=(string)$invoices->type_id;
		$_POST[preference_id]=(string)$invoices->preference_id;
		$_POST[date]=(string)$invoices->date;
		$_POST[note]=(string)$invoices->note;
		$_POST[customField1]=(string)$invoices->custom_field1;
		$_POST[customField2]=(string)$invoices->custom_field2;
		$_POST[customField3]=(string)$invoices->custom_field3;
		$_POST[customField4]=(string)$invoices->custom_field4;
		$_POST[invoice_items]=$invoices->invoice_items;
		
		
		$i = 0; 
		$j = 0;
		foreach($_POST[invoice_items]->invoice_item as $invoiceItem)
		{
		  $_POST[invoice_item_id][$i]=(string)$invoiceItem->invoice_item_id;
		  $_POST[quantity][$i]=(string)$invoiceItem->quantity;
		  $_POST[product_id][$i]=(string)$invoiceItem->product_id;
		  $_POST[unit_price][$i]=(string)$invoiceItem->unit_price;
		  
		  if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $_POST[quantity][$i]))
		  {
			 return "Inavlid number format for quantity of item".($i+1)."!" ;
		  }
		  
		  if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $_POST[unit_price][$i]))
		  {
			 return "Inavlid number format for unit_price of item".($i+1)."!" ;
		  }
          //Type_id is not total=1
		  if ($_POST[type_id]!=1 && (!is_numeric($_POST[product_id][$i]) || trim($_POST[product_id][$i])<=0 ))
		  {
			 return "Not valid product_id of item".($i+1)."!" ;
		  }
		  if (trim($_POST[product_id][$i])!="" && !is_numeric($_POST[product_id][$i]))
		  {
		     return "Not numeric product_id of item".($i+1)."!" ;
		  }
		  
		  if (trim($_POST[invoice_item_id][$i])!="" && !is_numeric($_POST[invoice_item_id][$i]))
		  {
		     return "Not numeric invoice_item_id of item".($i+1)."!" ;
		  }
		  
		  //when more than one tax_id per item
		  $_POST[tax_id][$i]=array();
		  foreach($invoiceItem->tax_id as $tax)
		  {
			$taxID =(string)$tax;
			if (trim($taxID)=="" || !is_numeric($taxID) || $taxID<=0)
			{
				return "Not valid tax_id of item".($i+1)."!" ;
			}
		    $_POST[tax_id][$i][] = $taxID; 
		  }
		  
		  $_POST[description][$i]=(string)$invoiceItem->description;
		  
		  //attributes-TODO: Maria if don't exist these ids; if it is not enabled the attribute for the product
		  foreach($invoiceItem->attributes->attribute as $attribute)
		  {
		    $attrID = (string)$attribute->prod_attribute_id;
			$valueID = (string)$attribute->prod_value_id;
		    $_POST["attribute"][$i][$attrID] = $valueID ;
		  }
		  
		  //"yes" as valid format - strtolower doesn't work for cyrillic
		  //so form xml receive 1 -true/0-false
		  $_POST['delete'][$i]=(string)$invoiceItem->delete;
		  if ($_POST['delete'][$i]=="1")
		  {
		     $_POST['delete'][$i]="yes";
		     $j++; 
		  }
		
		  $i++; 
		}
		
		
		if ($i==0)
		{
			return "No items for the invoice!";
		}
		
		if ($i==$j)
		{
			return "No items for the invoice after delete all of them!";
		}
		
		if (trim($_POST[date])=="")
		{
		    return "Inavlid length of date!";
		}
		
		//valid date in format YYYY-MM-DD
		$d = DateTime::createFromFormat('Y-m-d', $_POST[date]);
        if($d && $d->format('Y-m-d') == $_POST[date])
		{
		   //var_export( $_POST[date]);
		}
		else
		{
			return "Invalid date - must be in format YYYY-MM-DD";
		}
				
		if (!is_numeric($_POST[index_id]))
		{
			return "Not numeric index_id field!";
		}
		if (!is_numeric($_POST[biller_id]) || $_POST[biller_id]<=0 )
		{
			return "Not valid biller_id field!";
		}
		if (!is_numeric($_POST[customer_id]) || $_POST[customer_id]<=0)
		{
			return "Not valid customer_id field!";
		}
		if (!is_numeric($_POST[type_id]) || $_POST[type_id]<=0)
		{
			return "Not valid type_id field!";
		}
		if (!is_numeric($_POST[preference_id]) || $_POST[preference_id]<=0)
		{
			return "Not numeric preference_id field!";
		}
		//use mb_strlen instead of strlen because of cyrillic characters
		if (mb_strlen($_POST[customField1])>50)
		{
			return "Inavlid length of customField1!";
		}
		if (mb_strlen($_POST[customField2])>50)
		{
			return "Inavlid length of customField2!";
		}
		if (mb_strlen($_POST[customField3])>50)
		{
			return "Inavlid length of customField3!";
		}
		if (mb_strlen($_POST[customField4])>50)
		{
			return "Inavlid length of customField4!";
		}

		return "";
	}
	
	protected function GetInvoice($invoiceID)
	{
		$invoice;
		try
		{
			$invoice = getInvoice($invoiceID);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		if(!isset($invoice['id']))
		{
			//No resource at the specified URL
			header('HTTP/1.1 404 Not Found');
			exit();
		}

		$doc = new DOMDocument('1.0','UTF-8');
		$doc->formatOutput = true;
		
		$root_element = $doc->createElement("invoice");
		$doc->appendChild($root_element);
		$root_element->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $root_element->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		try
		{
			$this->CreateItemNodes($root_element,$doc,$invoice);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		//invoiceItems -start
		$invoice_items = $doc->createElement("invoice_items");
		$invoice_items = $root_element->appendChild($invoice_items);
		
		try
		{
			$invoiceItems = invoice::getInvoiceItems($invoiceID);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		foreach ($invoiceItems as $invoiceItem)
		{
			$itemNode = $doc->createElement("invoice_item");
			$itemNode = $invoice_items->appendChild($itemNode);
			try
			{
				$this->CreateInvoiceItemNodes($itemNode,$doc,$invoiceItem);
			}
			catch (Exception $e)
			{
				//An unexpected error occurred
				header('HTTP/1.1 500 Internal Server Error');
				exit();
			}
		}
		//invoiceItems -end

		return $doc;
	}
	
	public  function index()
	{
	  $doc = new DOMDocument('1.0','UTF-8');
	  $doc->formatOutput = true;
	  
	  $root_element = $doc->createElement("invoices");
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
	  
	  $invoices=array();
	  
	  try
	  {
			/*$invoice = new invoice();
			$large_dataset = getDefaultLargeDataset();
			if($large_dataset == $LANG['enabled'])
			{
			  $sth = $invoice->select_all('large');
			} 
			else 
			{
			  $sth = $invoice->select_all('');
			}
			$invoices = $sth->fetchAll(PDO::FETCH_ASSOC);*/
			$invoices = invoice::select_all_where();
	  }
	  catch (Exception $e){
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
	  }
	  
	  if(count($invoices)<=0)
	  {
			//Successful request when no data is returned
			header('HTTP/1.1 204 No Content');
			exit();
	  }
	  
	  foreach ($invoices as $invoice)
	  {
	    $itemNode = $doc->createElement("invoice");
		$itemNode = $root_element->appendChild($itemNode);
		try
		{
			$this->CreateItemNodes($itemNode,$doc,$invoice);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		//invoiceItems -start
		$invoice_items = $doc->createElement("invoice_items");
		$invoice_items = $itemNode->appendChild($invoice_items);
		
		try
		{
			$invoiceItems = invoice::getInvoiceItems($invoice['id']);
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		foreach ($invoiceItems as $invoiceItem)
		{
			$invoiceItemNode = $doc->createElement("invoice_item");
			$invoiceItemNode = $invoice_items->appendChild($invoiceItemNode);
			try
			{
				$this->CreateInvoiceItemNodes($invoiceItemNode,$doc,$invoiceItem);
			}
			catch (Exception $e)
			{
				//An unexpected error occurred
				header('HTTP/1.1 500 Internal Server Error');
				exit();
			}
		}
		//invoiceItems -end
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
		
		return $this->GetInvoice($this->_queryStr["id"]);
		
	}
     
	//Creates a new invoice
	public function post()
	{
	    global $dbh;
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
			$invoices = new SimpleXMLElement($this->_rawBody);
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($invoices,true);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
			if (insertInvoice($_POST[type_id])) 
			{
				$insertID = lastInsertId();
				if ($insertID<=0)
				{
					//An unexpected error occurred
					header('HTTP/1.1 500 Internal Server Error');
					exit();
				}
				$saved = true;
			}
			else
			{
					//An unexpected error occurred
					header('HTTP/1.1 500 Internal Server Error - can not save the invoice itself');
					exit();
			}
			
			//TODO :Maria total_id=1
			if($_POST[type_id]==1 && $saved) 
			{
			    $unitPrice=$_POST['unit_price'][0];
				$descript=$_POST['description'][0];
			    $_POST['description']="";
				$_POST['notes']="";
				$_POST['unit_price']=$_POST['unit_price'][0];
				insertProduct(0,0);
				$product_id = lastInsertId();
			
				if (insertInvoiceItem($insertID,1,$product_id,1,$_POST['tax_id'][0],$descript,$unitPrice))
				{
					//Successful request when something is created at another URL 
					header('HTTP/1.1 201 Created');
					return $this->GetInvoice($insertID);
				}
				else 
				{
					//An unexpected error occurred
					header('HTTP/1.1 500 Internal Server Error - invoice item not saved');
					exit();
				}
			}
			elseif ($saved) 
			{
				//$logger->log('Max items:'.$_POST['max_items'], Zend_Log::INFO);
				$i = 0;
				foreach($_POST[invoice_items]->invoice_item as $invoiceItem)
				{
					//$logger->log('i='.$i, Zend_Log::INFO);
					//$logger->log('qty='.$_POST["quantity$i"], Zend_Log::INFO);
					if($_POST["quantity"][$i] != null)
					{
						if(
						  insertInvoiceItem($insertID,$_POST["quantity"][$i],$_POST["product_id"][$i],$i,$_POST["tax_id"][$i],$_POST["description"][$i], $_POST["unit_price"][$i],$_POST["attribute"][$i])
						  ) 
						{
						} 
						else 
						{
							$saved=false;
						}
					}
					$i++;
				}
				
				if ($saved)
				{
					//Successful request when something is created at another URL 
					header('HTTP/1.1 201 Created');
				}
				else
				{
				    //An unexpected error occurred
					header('HTTP/1.1 500 Internal Server Error - Not all items saved');
					//exit();
				}
				return $this->GetInvoice($insertID);
				
			}
			
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
	}
	
	//Updates an existing invoice
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
		
			$invoices = new SimpleXMLElement($this->_rawBody);
			
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($invoices, false);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
		    $invoiceID = $this->_queryStr['id'];
			$_POST['id'] = $invoiceID;
			if (updateInvoice($invoiceID)) 
			{
				$saved = true;
			}
			else
			{
				//An unexpected error occurred
				header('HTTP/1.1 500 Internal Server Error - can not update the invoice itself');
				exit();
			}
			//type_id=1 total type
			if($_POST[type_id] == 1 && $saved) 
			{
				//$logger->log('Total style invoice updated, product ID: '.$_POST['products0'], Zend_Log::INFO);
				$sql = "UPDATE ".TB_PREFIX."products SET unit_price = :price, description = :description WHERE id = :id AND domain_id = :domain_id";
				dbQuery($sql,
					':price', $_POST['unit_price'][0],
					':description', $_POST['description'][0],
					':id', $_POST['product_id'][0],
					':domain_id', $this->_domain_id
					);
			}

			if ($saved)
			{
				//$logger->log('Max items:'.$_POST['max_items'], Zend_Log::INFO);
				$i = 0;
				foreach($_POST[invoice_items]->invoice_item as $invoiceItem)
				{
					//$logger->log('i='.$i, Zend_Log::INFO);
					//$logger->log('qty='.$_POST["quantity$i"], Zend_Log::INFO);
					//$logger->log('product='.$_POST["products$i"], Zend_Log::INFO);
					if($_POST["delete"][$i] == "yes")
					{ 
						//TODO : Maria check if delete from invoice_tax_item
						delete('invoice_items','id',$_POST["invoice_item_id"][$i]);
					}
					if($_POST["delete"][$i] !== "yes")
					{
						if($_POST["quantity"][$i] != null)
						{
							//new line item added in edit page
							if($_POST["invoice_item_id"][$i] == "" || $_POST["invoice_item_id"][$i] == 0)
							{
								if (
								      insertInvoiceItem($invoiceID,$_POST["quantity"][$i],$_POST["product_id"][$i],$i,$_POST["tax_id"][$i],$_POST["description"][$i], $_POST["unit_price"][$i],$_POST["attribute"][$i])
								    )
								{
								}
								else
								{
								   
								}
							}
							
							if($_POST["invoice_item_id"][$i] != "" || $_POST["invoice_item_id"][$i]>0)
							{
								if (
								    updateInvoiceItem($_POST["invoice_item_id"][$i],$_POST["quantity"][$i],$_POST["product_id"][$i],$i,$_POST["tax_id"][$i],$_POST["description"][$i], $_POST["unit_price"][$i],$_POST["attribute"][$i])
								   )
								{
								}
								else
								{
								
								}
							}
						}
					}
					$i++;
				}
				
				//Successful request when something is updated at another URL 
				header('HTTP/1.1 200 OK - Updated successfully');
				return $this->GetInvoice($invoiceID);
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