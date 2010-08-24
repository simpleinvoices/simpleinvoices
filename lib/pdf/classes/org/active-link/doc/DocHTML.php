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

import("org.active-link.xml.XML");
import("org.active-link.doc.PHPClass");
import("org.active-link.doc.Method");

/**
  *	DocHTML parses PHP class file comments and generates documentation
  *	@class		DocHTML
  *	@package	org.active-link.doc
  *	@author		Zurab Davitiani
  *	@version	0.3.4
  *	@requires	XML, PHPClass, Method
  *	@see		PHPClass
  */

class DocHTML {

	var $CSSFile;
	var $CSSFileTag;
	var $CSSString;
	var $CSSStringTag;
	var $CSSStringDefault;

	/**
	  *	Constructor, runs when new object instance is created, sets default values
	  *	@method		DocHTML
	  */
	function DocHTML() {
		$this->CSSStringDefault = "
		body {background-color: white;}
		a {font-family: monospace;}
		ul {list-style-type: none;}
		.classTitle {color: blue;}
		.name {color: black;}
		.version {color: black;}
		.requires {color: red;}
		.extends {color: black;}
		.description {color: black;font-family: sans-serif;}
		.author {color: blue;}
		.methodsTitle {color: blue;}
		.methodList {color: blue;}
		.methodName {color: blue;font-weight: bold;}
		.returns {color: black;}
		.param {color: black;font-weight: bold;font-family: monospace;}
		";
	}

	/**
	  *	Returns class documentation as a string, formatted in HTML
	  *	If argument is a filename, it parses the file for comments and generates documentation
	  *	If argument is an object of type PHPClass, then documentation is generated from it
	  *	@method		getClassDoc
	  *	@param		mixed argument
	  *	@returns	string HTML-formatted documentation if successful, false otherwise
	  */
	function getClassDoc($argument) {
		if(is_object($argument) && get_class($argument) == "phpclass")
			return $this->getClassDocFromClass($argument);
		elseif(is_string($argument))
			return $this->getClassDocFromFile($argument);
		else
			return false;
	}

	/**
	  *	Returns class documentation as a string, formatted in HTML
	  *	@method		getClassDocFromClass
	  *	@param		object objClass
	  *	@returns	string HTML-formatted documentation if successful, false otherwise
	  */
	function getClassDocFromClass($objClass) {
		if(is_object($objClass) && get_class($objClass) == "phpclass") {
			$classDocXML = new XML("html");
			// ---------------------- HEAD ---------------------- //
			$headXML = new XMLBranch("head");
			$headXML->setTagContent($objClass->getInfo("name"), "head/title");
			$headXML->setTagContent("", "head/meta");
			$headXML->setTagAttribute("http-equiv", "content-type", "head/meta");
			$headXML->setTagAttribute("content", "text/html; charset=ISO-8859-1", "head/meta");
			$headXML->setTagContent($this->CSSStringDefault, "head/style");
			$headXML->setTagAttribute("type", "text/css", "head/style");
			// ---------------------- BODY ---------------------- //
			$bodyXML = new XMLBranch("body");
			$classTitleXML = new XMLBranch("h1");
			$classTitleXML->setTagAttribute("class", "classTitle");
			$classTitleXML->setTagContent($objClass->getInfo("name") . " Class");
			$bodyXML->addXMLBranch($classTitleXML);
			foreach($objClass->info as $infoKey => $infoValue) {
				$brXML = new XMLBranch("br");
				$bodyXML->addXMLBranch($brXML);
				if(is_array($infoValue)) {
					$spanXML = new XMLBranch("span");
					$spanXML->setTagAttribute("class", $infoKey);
					$spanXML->setTagContent(ucfirst($infoKey) . ":");
					$ulXML = new XMLBranch("ul");
					$ulXML->setTagAttribute("class", $infoKey);
					foreach($infoValue as $value) {
						$liXML = new XMLBranch("li");
						$liXML->setTagContent($value);
						$ulXML->addXMLBranch($liXML);
					}
					$bodyXML->addXMLBranch($spanXML);
					$bodyXML->addXMLBranch($ulXML);
				}
				else {
					$spanXML = new XMLBranch("span");
					$spanXML->setTagAttribute("class", $infoKey);
					$spanXML->setTagContent(ucfirst($infoKey) . ": " . $infoValue);
					$bodyXML->addXMLBranch($spanXML);
				}
			}
			$hrXML = new XMLBranch("hr");
			$bodyXML->addXMLBranch($hrXML);
			$h2XML = new XMLBranch("h2");
			$h2XML->setTagAttribute("class", "methodsTitle");
			$h2XML->setTagContent("All Methods");
			$bodyXML->addXMLBranch($h2XML);
			$spanXML = new XMLBranch("span");
			$spanXML->setTagAttribute("class", "methodList");
			foreach($objClass->methods as $methodName => $method) {
				$aMethodXML = new XMLBranch("a");
				$aMethodXML->setTagAttribute("href", "#" . $methodName);
				$aMethodXML->setTagContent($methodName);
				$brXML = new XMLBranch("br");
				$spanXML->addXMLBranch($aMethodXML);
				$spanXML->addXMLBranch($brXML);
			}
			$bodyXML->addXMLBranch($spanXML);
			foreach($objClass->methods as $methodName => $method) {
				$hrXML = new XMLBranch("hr");
				$bodyXML->addXMLBranch($hrXML);
				$pMethodXML = new XMLBranch("p");
				$aMethodXML = new XMLBranch("a");
				$aMethodXML->setTagAttribute("name", $methodName);
				$spanXMLName = new XMLBranch("span");
				$spanXMLName->setTagAttribute("class", "methodName");
				$spanXMLName->setTagContent($methodName);
				$spanXMLArgs = new XMLBranch("span");
				$tagContentArgs = " ( ";
				if(is_array($method->params) && count($method->params) > 0) {
					$paramCount = 0;
					foreach($method->params as $key => $value) {
						if($paramCount > 0)
							$tagContentArgs .= ", ";
						$tagContentArgs .= $key;
						$paramCount ++;
					}
				}
				$tagContentArgs .= " )";
				$spanXMLArgs->setTagContent($tagContentArgs);
				$aMethodXML->addXMLBranch($spanXMLName);
				$aMethodXML->addXMLBranch($spanXMLArgs);
				$pMethodXML->addXMLBranch($aMethodXML);
				$bodyXML->addXMLBranch($pMethodXML);
				unset($method->info["name"]);
				foreach($method->info as $infoKey => $infoValue) {
					if(is_array($infoValue)) {
						$pXML = new XMLBranch("p");
						$pXML->setTagAttribute("class", $infoKey);
						$pXML->setTagContent(ucfirst($infoKey) . ":");
						$ulXML = new XMLBranch("ul");
						$ulXML->setTagAttribute("class", $infoKey);
						foreach($infoValue as $value) {
							$liXML = new XMLBranch("li");
							$liXML->setTagContent($value);
							$ulXML->addXMLBranch($liXML);
						}
						$bodyXML->addXMLBranch($pXML);
						$bodyXML->addXMLBranch($ulXML);
					}
					else {
						$pXML = new XMLBranch("p");
						$pXML->setTagAttribute("class", $infoKey);
						$pXML->setTagContent(ucfirst($infoKey) . ": " . $infoValue);
						$bodyXML->addXMLBranch($pXML);
					}
				}
				if(is_array($method->params) && count($method->params) > 0) {
					$pParamXML = new XMLBranch("p");
					//$pParamXML->setTagAttribute("class", "param");
					$paramTitleXML = new XMLBranch("span");
					$paramTitleXML->setTagContent("Arguments:");
					$pParamXML->addXMLBranch($paramTitleXML);
					$paramListXML = new XMLBranch("ul");
					foreach($method->params as $key => $value) {
						$paramItemXML = new XMLBranch("li");
						$paramItemXML->setTagAttribute("class", "param");
						$paramItemXML->setTagContent($key);
						$paramListXML->addXMLBranch($paramItemXML);
					}
					$pParamXML->addXMLBranch($paramListXML);
					$bodyXML->addXMLBranch($pParamXML);
				}
			}
			// ---------------------- END  ---------------------- //
			$classDocXML->addXMLBranch($headXML);
			$classDocXML->addXMLBranch($bodyXML);
			return $classDocXML->getXMLString(0);
		}
		else
			return false;
	}

	/**
	  *	Returns class documentation as a string, formatted in HTML
	  *	@method		getClassDocFromFile
	  *	@param		string filename
	  *	@returns	string HTML-formatted documentation if successful, false otherwise
	  */
	function getClassDocFromFile($filename) {
		if(is_string($filename) && file_exists($filename) && is_readable($filename)) {
			$objClass = new PHPClass($filename);
			return $this->getClassDocFromClass($objClass);
		}
		else
			return false;
	}

}
