<?php

/*
	This file is part of ActiveLink PHP DOC Package (www.active-link.com).
	Copyright (c) 2002-2004 by Zurab Davitiani

	You can contact the author of this software via E-mail at
	hattrick@mailcan.com

	ActiveLink PHP DOC Package is free software; you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation; either version 2.1 of the License, or
	(at your option) any later version.

	ActiveLink PHP DOC Package is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with ActiveLink PHP DOC Package; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

import("org.active-link.doc.Method");

/**
  *	PHPClass class provides a structural definition for a class
  *	@class		PHPClass
  *	@package	org.active-link.doc
  *	@author		Zurab Davitiani
  *	@version	0.3.4
  *	@requires	Method
  *	@see		PHPClass
  */

class PHPClass {

	var $methods;
	var $properties;
	var $info;

	/**
	  *	Constructor, if filename is supplied parses the file into the object
	  *	@method		PHPClass
	  *	@param		optional string filename
	  *	@returns	none
	  */
	function PHPClass($filename = "") {
		$this->methods = array();
		$this->properties = array();
		$this->info = array();
		if($filename != "")
			$this->parseFromFile($filename);
	}

	/**
	  *	Deletes a property by name
	  *	@method		deleteInfo
	  *	@param		string name
	  *	@returns	true if successful, false otherwise
	  */
    function deleteInfo($name) {
		$success = false;
		if(array_key_exists($name, $this->info)) {
			unset($this->info[$name]);
			$success = true;
		}
		return $success;
	}

	/**
	  *	Returns a property value by name
	  *	@method		getInfo
	  *	@param		string name
	  *	@returns	string value if successful, false otherwise
	  */
    function getInfo($name) {
		if(array_key_exists($name, $this->info))
			return $this->info[$name];
		else
			return false;
	}

	/**
	  *	Parses a class from supplied filename
	  *	@method		parseFromFile
	  *	@param		string filename
	  *	@returns	true if successful, false otherwise
	  */
	function parseFromFile($filename) {
		$success = false;
		if(file_exists($filename) && is_readable($filename)) {
			$arrContents = file($filename);
			$parsing = false;
			$parsingBlocks = array();
			$tempBlock = array();
			foreach($arrContents as $line) {
				if(trim($line) == "/**") {
					$parsing = true;
					$blockstart = true;
				}
				elseif($parsing && trim($line) == "*/") {
					$parsing = false;
					$parsingBlocks[] = $tempBlock;
					$tempBlock = array();
				}
				else {
					if($parsing) {
						if($blockstart) {
							$tempBlock[] = $line;
							$blockstart = false;
						}
						else {
							$tempBlock[] = $line;
						}
					}
				}
			}
			foreach($parsingBlocks as $blockLines) {
				$block = array();
				foreach($blockLines as $line) {
					$str = strstr($line, "@");
					$str = substr($str, 1);
					if($str !== false) {
						$separatorPos = (strpos($str, " ") && strpos($str, "\t")) ? min(strpos($str, " "), strpos($str, "\t")) : (strpos($str, " ") ? strpos($str, " ") : (strpos($str, "\t") ? strpos($str, "\t") : strlen($str)));
						$name = trim(substr($str, 0, $separatorPos));
						$value = trim(substr($str, $separatorPos));
					}
					else {
						$name = "description";
						$value = trim($line);
					}
					if($name == "param" || $name == "description")
						$block[$name][] = $value;
					else
						$block[$name] = $value;
				}
				//print("<pre>");
				//print_r($block);
				//print("</pre>");
				if(array_key_exists("method", $block)) {
					$tempMethod = new Method($block["method"]);
					unset($block["method"]);
					if(isset($block["param"]) && is_array($block["param"])) {
						foreach($block["param"] as $param) {
							$tempMethod->setParam($param, "");
						}
					}
					unset($block["param"]);
					foreach($block as $name => $value) {
						$tempMethod->setInfo($name, $value);
					}
					$this->setMethod($tempMethod);
				}
				elseif(array_key_exists("class", $block)) {
					$this->setInfo("name", $block["class"]);
					unset($block["class"]);
					foreach($block as $name => $value) {
						$this->setInfo($name, $value);
					}
				}
			}
			$success = true;
		}
		return $success;
	}

	/**
	  *	Sets a property by name
	  *	@method		setInfo
	  *	@param		string name, string value
	  *	@returns	none
	  */
	function setInfo($name, $value) {
		$this->info[$name] = $value;
	}

	/**
	  *	Adds a method to the class definition
	  *	@method		setMethod
	  *	@param		object method
	  *	@returns	true if successful, false otherwise
	  */
	function setMethod($method) {
		$success = false;
		if(is_object($method) && get_class($method) == "method") {
			$this->methods[$method->getInfo("name")] = $method;
			$success = true;
		}
		return $success;
	}

}

?>
