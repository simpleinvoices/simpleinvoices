<?php

/*
	This file is part of ActiveLink PHP NET Package (www.active-link.com).
	Copyright (c) 2002-2004 by Zurab Davitiani

	You can contact the author of this software via E-mail at
	hattrick@mailcan.com

	ActiveLink PHP NET Package is free software; you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation; either version 2.1 of the License, or
	(at your option) any later version.

	ActiveLink PHP NET Package is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with ActiveLink PHP NET Package; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
 *	requires Socket class
 */
import("org.active-link.net.Socket");

/**
  *	HTTPClient class provides HTTP request functionality and ability to retrieve response
  *	@class		HTTPClient
  *	@package	org.active-link.net
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@extends	Socket
  *	@requires	Socket
  *	@see		Socket
  */

class HTTPClient extends Socket {

	// protected properties
	var $defaultRequestMethod;
	var $defaultRequestURI;
	var $defaultRequestVersion;
	var $defaultRequestUserAgent;
	var $defaultRequestBody;
	var $requestMethod;
	var $requestURI;
	var $requestVersion;
	var $requestUserAgent;
	var $requestHeaders;

	/**
	  *	HTTP client class constructor accepts host (required) and port (optional, default 80) arguments
	  *	@method		HTTPClient
	  *	@param		string host
	  *	@param		optional int port
	  */
	function HTTPClient($host, $port = 80) {
		$this->Socket($host, $port);
		$this->defaultRequestMethod = "GET";
		$this->defaultRequestURI = "/";
		$this->defaultRequestVersion = "HTTP/1.0";
		$this->defaultRequestUserAgent = "ActiveLink NET Object/0.3.3";
		$this->defaultRequestBody = "";
		$this->requestMethod = $this->defaultRequestMethod;
		$this->requestURI = $this->defaultRequestURI;
		$this->requestVersion = $this->defaultRequestVersion;
		$this->requestUserAgent = $this->defaultRequestUserAgent;
		$this->requestBody = $this->defaultRequestBody;
		$this->requestHeaders = array();
	}

	/**
	  *	Adds a supplied raw header to the internal header array
	  *	@method		addRequestHeaderRaw
	  *	@param		string header
	  *	@returns	none
	  */
	function addRequestHeaderRaw($header) {
		$this->requestHeaders[] = $header;
	}

	/**
	  *	Gets a string containing all HTTP request headers in their raw form
	  *	@method		getRequestHeaders
	  *	@returns	string request HTTP headers
	  */
	function getRequestHeaders() {
		$headers = $this->requestMethod . " " . $this->requestURI . " " . $this->requestVersion . "\r\n";
		$headers .= "User-Agent: " . $this->requestUserAgent . "\r\n";
		$headers .= "Host: " . $this->host . "\r\n";
		foreach($this->requestHeaders as $header) {
			$headers .= $header . "\r\n";
		}
		if($this->requestMethod == "POST") {
			$contentLength = strlen($this->requestBody);
			$headers .= "Content-length: " . $contentLength . "\r\n";
		}
		$headers .= "Connection: close\r\n\r\n";
		return $headers;
	}

	/**
	  *	Sets HTTP request body/payload, used only when request method is POST
	  *	@method		setRequestBody
	  *	@param		string body
	  *	@returns	none
	  */
	function setRequestBody($body) {
		$this->requestBody = $body;
	}

	/**
	  *	Sets HTTP request method, GET or POST
	  *	@method		setRequestMethod
	  *	@param		string method
	  *	@returns	none
	  */
	function setRequestMethod($method) {
		$this->requestMethod = strtoupper($method);
	}

	/**
	  *	Sets request URI, if not set here, default will be /
	  *	@method		setRequestURI
	  *	@param		string uri
	  *	@returns	none
	  */
	function setRequestURI($uri) {
		$this->requestURI = $uri;
	}

	/**
	  *	Sets HTTP request User-Agent to send to the server, default is "ActiveLink NET Object/version"
	  *	@method		setRequestUserAgent
	  *	@param		string userAgent
	  *	@returns	none
	  */
	function setRequestUserAgent($userAgent) {
		$this->setRequestUserAgent = $userAgent;
	}

	/**
	  *	Sets HTTP protocol version to be used, default is "HTTP/1.0"
	  *	@method		setRequestVersion
	  *	@param		string version
	  *	@returns	none
	  */
	function setRequestVersion($version) {
		$this->requestVersion = $version;
	}

	/**
	  *	After all settings are complete, send the request to the server
	  *	@method		sendRequest
	  *	@returns	string server response if successful, false otherwise
	  */
	function sendRequest() {
		$response = false;
		$request = $this->getRequestHeaders();
		$request .= $this->requestBody;
		$success = $this->connect();
		if($success) {
			$response = $this->sendReceive($request);
			$this->disconnect();
		}
		return $response;
	}

}
