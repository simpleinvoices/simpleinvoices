<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//authentication - send in header X-API-KEY
if (!isset($_SERVER["HTTP_X_API_KEY"]))
{
	header('HTTP/1.1 406 Not Acceptable - No API Key provided');//or 404 Not Found
	exit();
}
else
{
	$xapikey =$_SERVER["HTTP_X_API_KEY"]; //$this->_request->getHeader('X-API-KEY'); 
	if ($config->xapikey!=$xapikey) //sha1 php crypt returns hash
	{
		header('HTTP/1.1 401 Unauthorized - Invalid API Key');//or 403 not Forbidden
	    exit();
	}
} 
 
$request = array(); 
 
if (!array_key_exists('REQUEST_METHOD',$_SERVER) || !isset($_SERVER["REQUEST_METHOD"]))
{
	header('HTTP/1.1 400 Bad Request');//http_response_code(400);
	exit();
}
else
{
	//$_REQUEST["method"] = $_SERVER["REQUEST_METHOD"];
	$request['method'] = strtolower($_SERVER["REQUEST_METHOD"]);
}


//if the method is get and there is not an id
if($request['method']=="get" && !isset($_REQUEST["id"]))
{
  $request['method']="index"; //return all items
}
  
require_once 'Controllers/ProductsController.php';

// Initiate the Zend_Rest_Server class object
$server = new Zend_Rest_Server();
// set our Simple Rest Server class to handle the 
// Rest Service Handling
//$server->setClass('Simple_Rest_Server');
$server->setClass('Products');
// calling handle() will map and make available the 
// functions contained in Simple_Rest_Server
// for web service call from a client
$server->handle($request);