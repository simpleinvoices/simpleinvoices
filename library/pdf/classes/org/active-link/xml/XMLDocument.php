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
 *	requires XML, Tag and File classes
 */
import("org.active-link.xml.XML");
import("org.active-link.sys.File");
import("org.active-link.xml.Tag");

/**
  *	XMLDocument class provides a document class for XML
  *	@class		XMLDocument
  *	@package	org.active-link.xml
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@extends	File
  *	@requires	File, XML, Tag
  *	@see		XML
  */

class XMLDocument extends File {

	// protected variables
	var $xml;
	var $tag;

	/**
	  *	If filename is set and fileOpenMode is one of the modes that allows file to be read then file is opened and its contents parsed
	  *	If filename is set and fileOpenMode is something other than above the appropriate file is opened/created
	  *	If filename is not set then no files are opened/parsed/created and object contains default values
	  *	@method		XMLDocument
	  *	@param		optional string filename
	  *	@param		optional string fileOpenMode
	  */
	function XMLDocument($filename = "", $fileOpenMode = "r") {
		$success = $this->File($filename, $fileOpenMode);
		$this->tag = new Tag();
		$this->tag->tagStartOpen = "<?";
		$this->tag->tagClose = "?>";
		if($this->connected && ($this->fileOpenMode == $this->fileOpenModeRead || $this->fileOpenMode == $this->fileOpenModeReadWrite)) {
			$fileContents = $this->getContents();
			$this->close();
			$this->parseFromString($fileContents);
		}
		else {
			$this->setDefaultXMLTag();
			$this->xml = new XML();
		}
		return $success;
	}

	/**
	  *	Returns the XML object containing actual XML tree; in PHP 4 make sure to use =& to get a reference instead of a copy
	  *	@method		getXML
	  *	@returns	object of type XML containing actual XML tree
	  */
	function getXML() {
		return $this->xml;
	}

	/**
	  *	Returns the XML string of a complete XML document
	  *	@method		getXMLString
	  *	@returns	string containing contents of XML document
	  */
	function getXMLString() {
		$xmlString = $this->tag->getTagString();
		$xmlString .= "\n\n";
		$xmlString .= $this->xml->getXMLString(0);
		return $xmlString;
	}

	/**
	  *	Parses XML document from supplied string, also called from constructor when parsing file contents
	  *	@method		parseFromString
	  *	@param		string XMLDocString
	  *	@returns	none
	  */
    function parseFromString($XMLDocString) {
		$tagPos = $this->tag->setTagFromString($XMLDocString);
		if($tagPos === false) {
			$tagPos = array(0 => 0, 1 => 0);
			$this->setDefaultXMLTag();
		}
		$xmlContents = trim(substr($XMLDocString, $tagPos[1]));
		$this->xml = new XML($xmlContents);
	}

	/**
	  *	Saves document contents to a supplied filename
	  *	@method		save
	  *	@param		string filename
	  *	@returns	true if successful, false otherwise
	  */
	function save($filename) {
		$success = $this->open($filename, $this->fileOpenModeWrite);
		if($success) {
			$bytesWritten = $this->write($this->getXMLString());
			if($bytesWritten <= 0)
				$success = false;
			$this->close();
		}
		return $success;
	}

	/**
	  *	(Re)sets XML version/encoding to default values
	  *	@method		setDefaultXMLTag
	  *	@returns	none
	  */
	function setDefaultXMLTag() {
		$this->tag->setTagName("xml");
		$this->tag->setAttribute("version", "1.0");
		$this->tag->setAttribute("encoding", "UTF-8");
	}

	/**
	  *	Sets encoding of the XML document
	  *	@method		setEncoding
	  *	@param		string encoding
	  *	@returns	none
	  */
	function setEncoding($encoding) {
		$this->tag->setAttribute("encoding", $encoding);
	}

	/**
	  *	Sets version of the XML document
	  *	@method		setVersion
	  *	@param		string version
	  *	@returns	none
	  */
	function setVersion($version) {
		$this->tag->setAttribute("version", $version);
	}

	/**
	  *	Sets XML object of the XMLDocument, sets/changes/updates XML content to the supplied XML tree, uses reference no copy is created
	  *	@method		setXML
	  *	@param		object xml
	  *	@returns	true if successful, false otherwise
	  */
	function setXML(&$xml) {
		$success = false;
		if(gettype($xml) == "object" && strtolower(get_class($xml)) == "xml") {
			$this->xml = &$xml;
			$success = true;
		}
		return $success;
	}

}
