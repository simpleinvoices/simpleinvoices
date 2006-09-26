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

/**
  *	Method class complements PHPClass and is used to define a class method
  *	@class		Method
  *	@package	org.active-link.doc
  *	@author		Zurab Davitiani
  *	@version	0.3.4
  *	@see		PHPClass
  */

class Method {

	var $params;
	var $info;

	/**
	  *	Constructor, runs when new object instance is created, sets name of the method
	  *	@method		Method
	  *	@param		string name
	  */
	function Method($name) {
		$this->info = array();
		$this->params = array();
		$this->setInfo("name", $name);
	}

	/**
	  *	Returns value of a property by name
	  *	@method		getInfo
	  *	@param		string name
	  *	@returns	string value of a property if found, false otherwise
	  */
    function getInfo($name) {
		if(array_key_exists($name, $this->info))
			return $this->info[$name];
		else
			return false;
	}

	/**
	  *	Sets a property with supplied name to a supplied value
	  *	@method		setInfo
	  *	@param		string name, string value
	  *	@returns	none
	  */
	function setInfo($name, $value) {
		$this->info[$name] = $value;
	}

	/**
	  *	Sets a parameter with supplied name and value
	  *	@method		setParam
	  *	@param		string name, string value
	  *	@returns	none
	  */
	function setParam($name, $value) {
		$this->params[$name] = $value;
	}

}
