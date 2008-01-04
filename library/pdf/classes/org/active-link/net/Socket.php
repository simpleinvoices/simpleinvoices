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

/**
  *	Socket class provides a basic network socket functionality
  *	@class		Socket
  *	@package	org.active-link.net
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  */

class Socket {

	// protected properties
	var $host;
	var $port;
	var $connected;
	var $connectionID;

	/**
	  *	Constructor, accepts host and port, initializes object
	  *	@method		Socket
	  *	@param		host
	  *	@param		port
	  */
	function Socket($host, $port) {
		$this->host = $host;
		$this->port = $port;
		$this->connected = false;
	}

	/**
	  *	Connects to host with specified settings, accepts connection timeout (optional, default 30)
	  *	@method		connect
	  *	@param		optional int connectionTimeout
	  *	@returns	true if successful, false otherwise
	  */
	function connect($connectTimeout = 30) {
		$this->connectionID = fsockopen($this->host, $this->port, $errorID, $errorDesc, $connectTimeout);
		if($this->connectionID === false) {
			return false;
		}
		else {
			$this->connected = true;
			return true;
		}
	}

	/**
	  *	Disconnects if already connected
	  *	@method		disconnect
	  *	@returns	true if successful, false otherwise
	  */
	function disconnect() {
		$success = fclose($this->connectionID);
		if($success)
			$this->connected = false;
		return $success;
	}

	/**
	  *	Receives data through connected socket, accepts chunk size (optional, default 4096)
	  *	@method		receive
	  *	@param		optional int chunkSize
	  *	@returns	string received data if successful, false otherwise
	  */
	function receive($chunkSize = 4096) {
		$receivedString = "";
		$success = false;
		if($this->connected) {
			while(!feof($this->connectionID)) {
				$receivedString .= fgets($this->connectionID, $chunkSize);
			}
			$success = true;
		}
		if($success)
			return $receivedString;
		else
			return false;
	}

	/**
	  *	Sends data through connected socket
	  *	@method		send
	  *	@param		string sendString
	  *	@returns	true if successful, false otherwise
	  */
	function send($sendString) {
		$success = false;
		if($this->connected)
			$success = fwrite($this->connectionID, $sendString);
		return $success;
	}

	/**
	  *	Combination of send and receive methods in one
	  *	@method		sendReceive
	  *	@param		sendString
	  *	@param		optional int connectionTimeout
	  *	@returns	string received data if successful, false otherwise
	  */
	function sendReceive($sendString, $receiveChunkSize = 4096) {
		$success = true;
		$receivedString = "";
		if($this->connected) {
			$bytesSent = $this->send($sendString);
			if($bytesSent === false)
				$success = false;
			if($success) {
				$receivedString = $this->receive($receiveChunkSize);
				if($receivedString === false)
					$success = false;
			}
		}
		if($success)
			return $receivedString;
		else
			return false;
	}

	/**
	  *	Sets host to make a connection to
	  *	@method		setHost
	  *	@param		string host
	  *	@returns	none
	  */
	function setHost($host) {
		$this->host = $host;
	}

	/**
	  *	Sets port to use for the connection
	  *	@method		setPort
	  *	@param		int port
	  *	@returns	none
	  */
	function setPort($port) {
		$this->port = $port;
	}

}
