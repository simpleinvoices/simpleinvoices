<?php

/*
	This file is part of ActiveLink PHP XML Package (www.active-link.com).
	Copyright (c) 2002-2004 by Zurab Davitiani

	You can contact the author of this software via E-mail at
	hattrick@mailcan.com

	ActiveLink PHP XML Package is free software; you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation; either version 2.1 of the License, or
	(at your option) any later version.

	ActiveLink PHP XML Package is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with ActiveLink PHP XML Package; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
 *	requires HTTPClient, XML and XMLDocument classes
 */

import("org.active-link.net.HTTPClient");
import("org.active-link.xml.XML");
import("org.active-link.xml.XMLDocument");

/**
  *	XMLRPCClient class provides XML-RPC client capabilities
  *	@class		XMLRPCClient
  *	@package	org.active-link.xml
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@extends	HTTPClient
  *	@requires	HTTPClient, XML, XMLDocument
  *	@see		HTTPClient
  */

class XMLRPCClient extends HTTPClient {

	var $xml;
	var $xmlDoc;
	var $params;

	/**
	  *	XMLRPCClient client class constructor accepts host (required) and port (optional, default 80) arguments
	  *	@method		XMLRPCClient
	  *	@param		string host
	  *	@param		optional int port
	  */
	function XMLRPCClient($host, $port = 80) {
		$this->HTTPClient($host, $port);
		$this->setRequestMethod("POST");
		$this->addRequestHeaderRaw("Content-type: text/xml");
		$this->xml = new XML("methodCall");
		$this->xml->setTagContent("", "methodCall/methodName");
		$this->xml->setTagContent("", "methodCall/params");
		$this->xmlDoc = new XMLDocument();
		$this->xmlDoc->setXML($this->xml);
		$paramsBranchArray = &$this->xml->getBranches("methodCall", "params");
		$this->params = &$paramsBranchArray[0];
		// this call not necessary if we can somehow update body before HTTPClient->sendRequest
		$this->setRequestBody($this->xmlDoc->getXMLString());
	}

	/**
	  *	Adds a parameter to a method call in XMLRPC request
	  *	@method		addParam
	  *	@param		string paramType
	  *	@param		mixed paramValue
	  *	@returns	none
	  */
	function addParam($paramType, $paramValue) {
		$newParam = new XMLBranch("param");
		$newParam->setTagContent($paramValue, "param/value/$paramType");
		$this->params->addXMLBranch($newParam);
		// this call not necessary if we can somehow update body before HTTPClient->sendRequest
		$this->setRequestBody($this->xmlDoc->getXMLString());
	}

	/**
	  *	Sets method name in XMLRPC request
	  *	@method		setMethodName
	  *	@param		string methodName
	  *	@returns	none
	  */
	function setMethodName ($methodName) {
		$this->xml->setTagContent($methodName, "methodCall/methodName");
		// this call not necessary if we can somehow update body before HTTPClient->sendRequest
		$this->setRequestBody($this->xmlDoc->getXMLString());
	}

	/**
	  *	Sets XMLRPC request by supplying an XMLDocument object
	  *	@method		setRequestXML
	  *	@param		object XMLDocument
	  *	@returns	true if successful, false otherwise
	  */
	function setRequestXML(&$XMLDocument) {
		if(is_object($XMLDocument) && strtolower(get_class($XMLDocument)) == "xmldocument") {
			$this->xmlDoc = &$XMLDocument;
			$this->xml = &$this->xmlDoc->getXML();
			$this->params = &$this->xml->getBranches("methodCall", "params");
			// this call not necessary if we can somehow update body before HTTPClient->sendRequest
			$this->setRequestBody(htmlspecialchars($this->xmlDoc->getXMLString()));
			$success = true;
		}
		else
			$success = false;
		return $success;
	}

}

?>
