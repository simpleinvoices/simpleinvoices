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
 *	requires XML class
 */
import("org.active-link.xml.XML");

/**
  *	XMLBranch class provides a tree-like structure to read/write/modify XML
  *	@class		XMLBranch
  *	@package	org.active-link.xml
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@extends	XML
  *	@requires	XML
  *	@see		Tree
  */

class XMLBranch extends XML {

	var $parentXML;

	/**
	  *	Gets parent object of the XML branch
	  *	@method		getParentXML
	  *	@returns	parent object of the XML branch
	  */
	function getParentXML() {
		return $this->parentXML;
	}

	/**
	  *	Sets parent object of the XML branch
	  *	@method		setParentXML
	  *	@param		object xml
	  *	@returns	true if successful, false otherwise
	  */
	function setParentXML(&$xml) {
		$success = false;
		if(strtolower(get_class($xml)) == "xml" || strtolower(get_class($xml)) == "xmlbranch") {
			$this->parentXML = &$xml;
			$success = true;
		}
		return $success;
	}

}

?>
