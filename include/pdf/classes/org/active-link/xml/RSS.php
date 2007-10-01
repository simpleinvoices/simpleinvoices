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

import("org.active-link.xml.XML");

/**
  *	Simple RSS class based on XML
  *	@class		RSS
  *	@package	org.active-link.xml
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@requires	XML
  *	@see		XML
  */

class RSS {

	var $xml;
	var $rootTags;
	var $itemBranches;

	/**
	  *	Constructor, parses the supplied RSS string into the object
	  *	@method		RSS
	  *	@param		string parseString
	  *	@returns	none
	  */
	function RSS($parseString) {
		$this->xml = new XML($parseString);
		$this->rootTags = array("rss", "rdf:RDF");
		$this->itemBranches = array();
		$this->parseItemBranches();
	}

	/**
	  *	Returns array of references to item branches of the RSS
	  *	@method		getItemBranches
	  *	@returns	array of references to objects of type XMLBranch (item branches of RSS)
	  */
	function getItemBranches() {
		return $this->itemBranches;
	}

	/**
	  *	Returns HTML-formatted RSS items
	  *	@method		getHTMLTitlesFormatted
	  *	@returns	string HTML-formatted RSS items
	  */
	function getHTMLTitlesFormatted() {
		$itemBranchesXML = new XML("ul");
		reset($this->itemBranches);
		foreach($this->itemBranches as $newsItem) {
			$itemXML = new XMLBranch("li");
			$itemLinkXML = new XMLBranch("a");
			$itemLinkXML->setTagContent($newsItem->getTagContent("item/title"));
			$itemLinkXML->setTagAttribute("href", $newsItem->getTagContent("item/link"));
			$itemXML->addXMLBranch($itemLinkXML);
			$itemBranchesXML->addXMLBranch($itemXML);
		}
		return $itemBranchesXML->getXMLString();
	}

	/**
	  *	Parses RSS item branches, called from constructor
	  *	@method		parseItemBranches
	  *	@returns	true if successful, false otherwise
	  */
	function parseItemBranches() {
		$success = false;
		$rootTagName = $this->xml->getTagName();
		if(in_array($rootTagName, $this->rootTags)) {
			$tempBranches = array();
			if($rootTagName == "rss")
				$tempBranches = $this->xml->getBranches($rootTagName . "/channel", "item");
			elseif($rootTagName == "rdf:RDF")
				$tempBranches = $this->xml->getBranches($rootTagName, "item");
			if($tempBranches !== false) {
				$this->itemBranches = $tempBranches;
				$success = true;
			}
		}
		return $success;
	}

}

?>
