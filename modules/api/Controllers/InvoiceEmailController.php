<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
class InvoiceEmail{

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
	
	protected function CreatePostBody($invEmail)
	{
	    try
		{
			$sql = "SELECT b.email AS email FROM ".TB_PREFIX."biller b, ".TB_PREFIX."invoices inv WHERE ( inv.id = :id AND b.id = inv.biller_id AND b.domain_id = inv.domain_id AND inv.domain_id = :domain_id)";
			$sth = dbQuery($sql,
						   ':domain_id', $this->_domain_id,	
						   ':id', $this->_queryStr['id']);
			$invBiller =$sth->fetch();
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error - Database error');
			exit();
		}
		
		$_POST['email_notes']=(string)$invEmail->email_notes;
		$_POST['email_from']=(string)$invEmail->email_from;
		$_POST['email_from_name']=(string)$invEmail->email_from_name;
		$_POST['email_to']=(string)$invBiller['email'];
		$_POST['email_bcc']=(string)$invEmail->email_bcc;
		$_POST['email_subject']=(string)$invEmail->email_subject;
		
		if (empty($_POST['email_from']))
		{
		    return "Inavlid length of email_from!";
		}
		
		if (empty($_POST['email_to']))
		{
		    return "Inavlid length of email_to!";
		}
		
		if (empty($_POST['email_subject']))
		{
		    return "Inavlid length of email_subject!";
		}
		
		return "";
	}
	
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
		
			$invEmail = new SimpleXMLElement($this->_rawBody);
			
		}
		catch (Exception $e)
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - Can not read the xml');
			exit();
		}

		$error = $this->CreatePostBody($invEmail);
		if ($error!="")
		{
			//Incorrect parameters specified on request
			header('HTTP/1.1 400 Bad Request - '.$error);
			exit();
		}
		
		try
		{
			//start - Add by Maria - to be equal the file name of export and mail 
			$invoice_id = $this->_queryStr['id'];

			$invoice = invoice::select($invoice_id);
			
			$spc2us_pref = str_replace(" ", "_", $invoice['index_name']);
			require_once('./library/pdf/destination._interface.class.php');
			$destination = new Destination();
			$pdf_file_name = $destination->filename_escape($spc2us_pref)  . '.pdf';
			//end
			
			// Create invoice
			$export = new export();
			$export -> format = "pdf";
			$export -> file_location = 'file';
			$export -> module = 'invoice';
			$export -> id = $this->_queryStr['id'];
			$export -> execute();

			#$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);

			$email = new email();
			$email -> format = 'invoice';
			$email -> notes = $_POST['email_notes'];
			$email -> from = $_POST['email_from'];
			$email -> from_friendly = $_POST['email_from_name'];//$biller['name'];
			$email -> to = $_POST['email_to'];
			$email -> bcc = $_POST['email_bcc'];
			$email -> subject = $_POST['email_subject'];
			$email -> attachment = $pdf_file_name;
			$message = $email -> send ();
			//var_export($pdf_file_name);
			exit();
			
		}
		catch (Exception $e)
		{
			//An unexpected error occurred
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}

	}
	
}