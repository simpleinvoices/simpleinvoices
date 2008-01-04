<?php

/*
	This file is part of ActiveLink PHP SYS Package (www.active-link.com).
	Copyright (c) 2002-2004 by Zurab Davitiani

	You can contact the author of this software via E-mail at
	hattrick@mailcan.com

	ActiveLink PHP SYS Package is free software; you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation; either version 2.1 of the License, or
	(at your option) any later version.

	ActiveLink PHP SYS Package is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with ActiveLink PHP SYS Package; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
  *	File class provides a wrapper around filesystem file functions
  *	@class		File
  *	@package	org.active-link.sys
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  */

class File {

	// protected variables
	var $filename;
	var $fileOpenMode;
	var $fileOpenModeRead;
	var $fileOpenModeReadWrite;
	var $fileOpenModeWrite;
	var $fileOpenModeWriteRead;
	var $fileOpenModeAppend;
	var $fileOpenModeAppendRead;
	var $connected;
	var $handleID;

	/**
	  *	Constructor accepts filename (optional) and open mode (optional, default "r")
	  *	If filename is specified, it is opened with the supplied open mode
	  *	@method 	File
	  *	@param		optional string filename
	  *	@param		optional string fileOpenMode
	  */
	function File($filename = "", $fileOpenMode = "r") {
		$success = true;
		$this->filename = $filename;
		$this->fileOpenMode = $fileOpenMode;
		$this->fileOpenModeRead = "r";
		$this->fileOpenModeReadWrite = "r+";
		$this->fileOpenModeWrite = "w";
		$this->fileOpenModeWriteRead = "w+";
		$this->fileOpenModeAppend = "a";
		$this->fileOpenModeAppendRead = "a+";
		$this->connected = false;
		$this->handleID = false;
		if($this->filename != "")
			$success = $this->open($this->filename, $this->fileOpenMode);
		return $success;
	}

	/**
	  *	Closes open file handle, resets filename, and file open mode to defaults
	  *	@method		close
	  *	@returns	true if successful, false otherwise
	  */
	function close() {
		$success = fclose($this->handleID);
		if($success) {
			$this->filename = "";
			$this->fileOpenMode = "r";
			$this->connected = false;
			$this->handleID = false;
		}
		return $success;
	}

	/**
	  *	Returns file contents, optionally specify chunk size number of bytes to use per chunk read (default 8192)
	  *	@method		getContents
	  *	@param		optional int chunkSize
	  *	@returns	string file contents if successful, false otherwise
	  */
	function getContents($chunkSize = 8192) {
		if($this->connected) {
			$fileContents = "";
			do {
				$data = fread($this->handleID, $chunkSize);
				if (strlen($data) == 0) {
					break;
				}
				$fileContents .= $data;
			} while(true);
			return $fileContents;
		}
		else
			return false;
	}

	/**
	  *	Returns file contents as an array of lines
	  *	@method		getContentsArray
	  *	@returns	array file contents lines
	  */
	function getContentsArray() {
		$fileContentsArray = file($this->filename);
		return $fileContentsArray;
	}

	/**
	  *	Opens a file with the supplied open mode
	  *	@method		open
	  *	@param		string filename
	  *	@param		optional string fileOpenMode
	  *	@returns	true if successful, false otherwise
	  */
	function open($filename, $mode = "r") {
		$success = false;
		if(!$this->connected) {
			$this->handleID = @fopen($filename, $mode);
			if($this->handleID !== false) {
				$this->filename = $filename;
				$this->fileOpenMode = $mode;
				$this->connected = true;
				$success = true;
			}
		}
		return $success;
	}

	/**
	  *	Writes supplied string content to already open file handle
	  *	@method		write
	  *	@param		string strContent
	  *	@returns	number of bytes written if successful, false otherwise
	  */
	function write($strContent) {
		$bytesWritten = fwrite($this->handleID, $strContent, strlen($strContent));
		return $bytesWritten;
	}

}

?>
