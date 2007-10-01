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

import("org.active-link.xml.Tag");
import("org.active-link.xml.Tree");

/**
  *	XML class provides a tree-like structure to read/write/modify XML
  *	@class		XML
  *	@package	org.active-link.xml
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@extends	Tree
  *	@requires	Tag, Tree, XMLBranch, XMLLeaf
  *	@see		Tree
  */

class XML extends Tree {

	// protected variables
	var $tag;
	var $pathSeparator;

	/**
	  *	If argument is an XML String it parses the string into XML object
	  *	If argument is a tag path, creates appropriate branches and tags
	  *	If argument is a simple string then sets that as a root tag name
	  *	@method		XML
	  *	@param		optional string argument
	  *	@returns	none
	  */
	function XML($argument = "") {
		$success = false;
		$this->Tree();
		$this->pathSeparator = "/";
		$this->tag = new Tag();
		if(is_string($argument)) {
			// if this is an XML string to be parsed
			if(strpos($argument, $this->tag->tagEndOpen) > 0 || strpos($argument, $this->tag->tagClose) > 0)
				$this->parseFromString($argument);
			// else if this is a tag path to be created
			elseif(strpos($argument, $this->pathSeparator) > 0) {
				$tags = explode($this->pathSeparator, $argument);
				$this->tag->setTagName($tags[0]);
				$this->setTagContent("", $argument);
			}
			else
				$this->tag->setTagName($argument);
			$success = true;
		}
		else
			$success = false;
		return $success;
	}

	/**
	  *	Adds another XML tree as a branch to the current XML object
	  *	@method		addXMLAsBranch
	  *	@param		object xml
	  *	@param		optional mixed id
	  *	@returns	true if successful, false otherwise
	  */
	function addXMLAsBranch($xml, $id = -1) {
		$success = false;
		if(is_object($xml) && strtolower(get_class($xml)) == "xml") {
			$newBranch = new XMLBranch();
			$newBranch->nodes = $xml->nodes;
			$newBranch->tag = $xml->tag;
			$success = $this->addXMLBranch($newBranch, $id);
		}
		return $success;
	}

	/**
	  *	Adds XML Branch to the current XML object
	  *	@method		addXMLBranch
	  *	@param		object xmlBranch
	  *	@param		optional mixed id
	  *	@returns	true if successful, false otherwise
	  */
	function addXMLBranch($xmlBranch, $id = -1) {
		$success = false;
		if(is_object($xmlBranch) && strtolower(get_class($xmlBranch)) == "xmlbranch") {
			$xmlBranch->setParentXML($this);
			$success = $this->addNode($id, $xmlBranch);
		}
		return $success;
	}

	/**
	  *	Adds XML Leaf to the current XML object
	  *	@method		addXMLLeaf
	  *	@param		object xmlLeaf
	  *	@param		optional mixed id
	  *	@returns	true if successful, false otherwise
	  */
	function addXMLLeaf($xmlLeaf, $id = -1) {
		$success = false;
		if(is_object($xmlLeaf) && strtolower(get_class($xmlLeaf)) == "xmlleaf") {
			$xmlLeaf->setParentXML($this);
			$success = $this->addNode($id, $xmlLeaf);
		}
		return $success;
	}

	/**
	  *	Retrieves an array of references to XMLBranches within the specified path, tag name, attribute name, and attribute value
	  *	@method		getBranches
	  *	@param		optional string tagPath
	  *	@param		optional string tagName
	  *	@param		optional string attrName
	  *	@param		optional string attrValue
	  *	@returns	array of references to XMLBranch objects that meet specified criteria, or false if none found
	  */
	function getBranches($tagPath = "", $tagName = "", $attrName = "", $attrValue = "") {
		$branchArray = array();
		if($tagPath == "")
			$tagPath = $this->tag->getTagName();
		$tags = explode($this->pathSeparator, $tagPath);
		if($this->tag->getTagName() == $tags[0]) {
			if(count($tags) == 1) {
				$arrKeys = array_keys($this->nodes);
				for($index = 0; $index < count($arrKeys); $index ++) {
					if(gettype($this->nodes[$arrKeys[$index]]) == "object" && strtolower(get_class($this->nodes[$arrKeys[$index]])) == "xmlbranch") {
						if(($tagName == "" || $this->nodes[$arrKeys[$index]]->tag->getTagName() == $tagName) &&
							($attrName == "" || $this->nodes[$arrKeys[$index]]->tag->attributeExists($attrName)) &&
							($attrValue == "" || $this->nodes[$arrKeys[$index]]->tag->getTagAttribute($attrName) == $attrValue)) {
							$branchArray[] = &$this->nodes[$arrKeys[$index]];
						}
					}
				}
			}
			else {
				$arrKeys = array_keys($this->nodes);
				for($index = 0; $index < count($arrKeys); $index ++) {
					if(gettype($this->nodes[$arrKeys[$index]]) == "object" && strtolower(get_class($this->nodes[$arrKeys[$index]])) == "xmlbranch") {
						if($this->nodes[$arrKeys[$index]]->tag->getTagName() == $tags[1]) {
							$newTagPath = implode($this->pathSeparator, array_slice($tags, 1));
							$newArray = $this->nodes[$arrKeys[$index]]->getBranches($newTagPath, $tagName, $attrName, $attrValue);
							if($newArray !== false)
								$branchArray = array_merge($branchArray, $newArray);
						}
					}
				}
			}
		}
		if(count($branchArray) == 0)
			$branchArray = false;
		return $branchArray;
	}

	/**
	  *	Retrieves an array of references to XMLLeaf(s) within the specified path
	  *	@method		getLeafs
	  *	@param		optional string tagPath
	  *	@returns	array of references to XMLLeaf objects in specified tag path, false if none found
	  */
	function getLeafs($tagPath = "") {
		$leafArray = array();
		if($tagPath == "")
			$tagPath = $this->tag->getTagName();
		$tags = explode($this->pathSeparator, $tagPath);
		if($this->tag->getTagName() == $tags[0]) {
			if(count($tags) == 1) {
				$arrKeys = array_keys($this->nodes);
				for($index = 0; $index < count($arrKeys); $index ++) {
					if(gettype($this->nodes[$arrKeys[$index]]) == "object" && strtolower(get_class($this->nodes[$arrKeys[$index]])) == "xmlleaf") {
						$leafArray[] = &$this->nodes[$arrKeys[$index]];
					}
				}
			}
			else {
				$arrKeys = array_keys($this->nodes);
				for($index = 0; $index < count($arrKeys); $index ++) {
					if(gettype($this->nodes[$arrKeys[$index]]) == "object" && strtolower(get_class($this->nodes[$arrKeys[$index]])) == "xmlbranch") {
						if($this->nodes[$arrKeys[$index]]->tag->getTagName() == $tags[1]) {
							$newTagPath = implode($this->pathSeparator, array_slice($tags, 1));
							$newArray = $this->nodes[$arrKeys[$index]]->getLeafs($newTagPath);
							if($newArray !== false)
								$leafArray = array_merge($leafArray, $newArray);
						}
					}
				}
			}
		}
		if(count($leafArray) == 0)
			$leafArray = false;
		return $leafArray;
	}

	/**
	  *	Returns attribute value of the specified tag and tagpath
	  *	@method		getTagAttribute
	  *	@param		string attributeName
	  *	@param		optional string tagPath
	  *	@returns	attribute of the specified tag if successful, false otherwise
	  */
	function getTagAttribute($attributeName, $tagPath = "") {
		if($tagPath == "")
			$tagPath = $this->tag->getTagName();
		$tags = explode($this->pathSeparator, $tagPath);
		$attributeValue = false;
		if($this->tag->getTagName() == $tags[0]) {
			if(sizeof($tags) == 1) {
				if($this->tag->attributeExists($attributeName))
					$attributeValue = $this->tag->getTagAttribute($attributeName);
			}
			else {
				foreach($this->nodes as $node) {
					if(strtolower(get_class($node)) == "xmlbranch")
						if($node->tag->getTagName() == $tags[1]) {
							$newTagPath = implode($this->pathSeparator, array_slice($tags, 1));
							$attributeValue = $node->getTagAttribute($attributeName, $newTagPath);
						}
				}
			}
		}
		return $attributeValue;
	}
	
	/**
	  *	Returns contents of the specified tag path
	  *	@method		getTagContent
	  *	@param		optional string tagPath
	  *	@returns	content of the tag from the specified path if successful, false otherwise
	  */
	function getTagContent($tagPath = "") {
		if($tagPath == "")
			$tagPath = $this->tag->getTagName();
		$tags = explode($this->pathSeparator, $tagPath);
		$tagValue = false;
		if($this->tag->getTagName() == $tags[0]) {
			if(sizeof($tags) == 1)
				$tagValue = $this->getXMLContent();
			else {
				foreach($this->nodes as $node) {
					if(strtolower(get_class($node)) == "xmlbranch")
						if($node->tag->getTagName() == $tags[1]) {
							$newTagPath = implode($this->pathSeparator, array_slice($tags, 1));
							$tagValue = $node->getTagContent($newTagPath);
						}
				}
			}
		}
		return $tagValue;
	}

	/**
	  *	Retrieves the tag name of the current object
	  *	@method		getTagName
	  *	@returns	tag name
	  */
	function getTagName() {
		return($this->tag->getTagName());
	}

	/**
	  *	Gets contents from the current object
	  *	@method		getXMLContent
	  *	@returns	contents of the current XML tag
	  */
	function getXMLContent() {
		$xmlContent = "";
		foreach($this->nodes as $node) {
			if(gettype($node) == "object") {
				if(strtolower(get_class($node)) == "xmlbranch")
					$xmlContent .= $node->getXMLString();
				elseif(strtolower(get_class($node)) == "xmlleaf")
					$xmlContent .= $node->getValue();
			}
		}
		return $xmlContent;
	}

	/**
	  *	Gets the whole XML string of the current object
	  *	@method		getXMLString
	  *	@param		optional mixed indent
	  *	@returns	complete XML string of current object
	  */
	function getXMLString($indent = false) {
		$xmlString = "";
		$containsBranches = false;
		$containsLeafs = false;
		$newIndent = false;
		if($indent === false)
			$newIndent = false;
		else {
			$newIndent = $indent + 1;
			$this->tag->setTagFormat($this->tag->FORMAT_INDENT, $indent);
		}
		foreach($this->nodes as $node) {
			if(gettype($node) == "object") {
				if(strtolower(get_class($node)) == "xmlbranch") {
					$this->tag->tagContent .= $node->getXMLString($newIndent);
					$containsBranches = true;
				}
				elseif(strtolower(get_class($node)) == "xmlleaf") {
					$this->tag->tagContent .= $node->getValue();
					$containsLeafs = true;
				}
			}
		}
		if($containsBranches)
			$this->tag->setTagFormatEndTag(true);
		$xmlString = $this->tag->getTagString();
		$this->tag->setTagContent("");
		return $xmlString;
	}

	/**
	  *	Find out whether the current object has any branches
	  *	@method		hasBranch
	  *	@returns	true if branches exist, false otherwise
	  */
	function hasBranch() {
		$hasBranch = false;
		foreach($this->nodes as $node) {
			if(strtolower(get_class($node)) == "xmlbranch") {
				$hasBranch = true;
				break;
			}
		}
		return $hasBranch;
	}

	/**
	  *	Find out whether the current object has any leaf(s)
	  *	@method		hasLeaf
	  *	@returns	true if leaf(s) exist, false otherwise
	  */
	function hasLeaf() {
		$hasLeaf = false;
		foreach($this->nodes as $node) {
			if(strtolower(get_class($node)) == "xmlleaf") {
				$hasLeaf = true;
				break;
			}
		}
		return $hasLeaf;
	}

	/**
	  *	Parse entire XML string into the current object; also called from constructor
	  *	@method		parseFromString
	  *	@param		string parseString
	  *	@returns	none
	  */
	function parseFromString($parseString) {
		$tagResult = $this->tag->setTagFromString($parseString);
		if($tagResult !== false) {
			$this->parseNodesFromTag();
			$this->tag->setTagContent("");
		}
	}

	/**
	  *	Parses the current tag content into Branches and Leaf(s); called from parseFromString
	  *	@method		parseNodesFromTag
	  *	@returns	none
	  */
	function parseNodesFromTag() {
		$tempTag = new Tag();
		$parseString = $this->tag->getTagContent();
		while($tagParsed = $tempTag->setTagFromString($parseString)) {
			if($tagParsed[0] != 0 && substr($parseString, 0, $tagParsed[0]) != "")
				$this->addXMLLeaf(new XMLLeaf(substr($parseString, 0, $tagParsed[0])));
			$branch = new XMLBranch();
			$tempTagCopy = new Tag();
			$tempTagCopy->setTagName($tempTag->getTagName());
			$tempTagCopy->tagAttributes = $tempTag->tagAttributes;
			$tempTagCopy->setTagContent($tempTag->getTagContent());
			$branch->setTag($tempTagCopy);
			$branch->parseNodesFromTag();
			$branch->tag->setTagContent("");
			$this->addXMLBranch($branch);
			$parseString = substr($parseString, $tagParsed[1]);
		}
		if(strlen($parseString) > 0 && $parseString != "")
			$this->addXMLLeaf(new XMLLeaf($parseString));
	}

	/**
	  *	Removes all Branches from current object
	  *	@method		removeAllBranches
	  */
	function removeAllBranches() {
		foreach($this->nodes as $key => $value) {
			if(strtolower(get_class($value)) == "xmlbranch")
				unset($this->nodes[$key]);
		}
	}

	/**
	  *	Removes all Leaf(s) from current object
	  *	@method		removeAllLeafs
	  */
	function removeAllLeafs() {
		foreach($this->nodes as $key => $value) {
			if(strtolower(get_class($value)) == "xmlleaf")
				unset($this->nodes[$key]);
		}
	}

	/**
	  *	Removes Branches with the specified criteria
	  *	@method		removeBranches
	  *	@param		optional string tagPath
	  *	@param		optional string tagName
	  *	@param		optional string attrName
	  *	@param		optional string attrValue
	  *	@returns	number of branches deleted
	  */
	function removeBranches($tagPath = "", $tagName = "", $attrName = "", $attrValue = "") {
		$branchesDeleted = 0;
		$referencedBranches = array();
		$tags = explode($this->pathSeparator, $tagPath);
		if(count($tags) > 1) {
			$parentTagName = array_pop($tags);
			$parentTagPath = implode($this->pathSeparator, $tags);
			$referencedBranches = $this->getBranches($parentTagPath, $parentTagName);
		}
		else {
			$referencedBranches[] = &$this;
		}
		for($i = 0; $i < count($referencedBranches); $i ++) {
			$arrKeys = array_keys($referencedBranches[$i]->nodes);
			for($index = 0; $index < count($arrKeys); $index ++) {
				if(gettype($referencedBranches[$i]->nodes[$arrKeys[$index]]) == "object" && strtolower(get_class($referencedBranches[$i]->nodes[$arrKeys[$index]])) == "xmlbranch") {
					if(($tagName == "" || $referencedBranches[$i]->nodes[$arrKeys[$index]]->tag->getTagName() == $tagName) &&
						($attrName == "" || $referencedBranches[$i]->nodes[$arrKeys[$index]]->tag->attributeExists($attrName)) &&
						($attrValue == "" || $referencedBranches[$i]->nodes[$arrKeys[$index]]->tag->getTagAttribute($attrName) == $attrValue)) {
						$referencedBranches[$i]->removeNode($arrKeys[$index]);
						$branchesDeleted ++;
					}
				}
			}
		}
		return $branchesDeleted;
	}

	/**
	  *	Sets tag object of a branch specified by branch ID for the current object; see getBranches and setTag
	  *	@method		setBranchTag
	  *	@param		mixed branchId
	  *	@param		object tag
	  *	@returns	true on success, false otherwise
	  */
	function setBranchTag($branchId, $tag) {
		$success = true;
		if(strtolower(get_class($this->nodes[$branchId])) == "xmlbranch" && strtolower(get_class($tag)) == "tag")
			$this->nodes[$branchId]->setTag($tag);
		else
			$success = false;
		return $success;
	}

	/**
	  *	Sets tag object of the current object
	  *	@method		setTag
	  *	@param		object tag
	  *	@returns	true if successful, false otherwise
	  */
	function setTag($tag) {
		$success = true;
		if(strtolower(get_class($tag)) == "tag")
			$this->tag = $tag;
		else
			$success = false;
		return $success;
	}

	/**
	  *	Sets an attribute name and value on an existing tag found via tagpath string
	  *	@method		setTagAttribute
	  *	@param		string attributeName
	  *	@param		optional string attributeValue
	  *	@param		optional string tagPath
	  *	@returns	true if successful, false otherwise
	  */
	function setTagAttribute($attributeName, $attributeValue = "", $tagPath = "") {
		if($tagPath == "")
			$tagPath = $this->tag->getTagName();
		$success = true;
		$tags = explode($this->pathSeparator, $tagPath);
		if($this->tag->getTagName() == $tags[0]) {
			if(sizeof($tags) == 1)
				$this->tag->setAttribute($attributeName, $attributeValue);
			else {
				$nodeTagFound = false;
				reset($this->nodes);
				$arrKeys = array_keys($this->nodes);
				for($index = 0; $index < count($arrKeys); $index ++) {
					$node =& $this->nodes[$arrKeys[$index]];
					if(strtolower(get_class($node)) == "xmlbranch")
						if($node->tag->getTagName() == $tags[1]) {
							$newTagPath = implode($this->pathSeparator, array_slice($tags, 1));
							$success = $node->setTagAttribute($attributeName, $attributeValue, $newTagPath);
							$nodeTagFound = true;
						}
				}
				if(!$nodeTagFound)
					$success = false;
			}
		}
		else
			$success = false;
		return $success;
	}

	/**
	  *	Sets content of the specified tag
	  *	@method		setTagContent
	  *	@param		mixed content
	  *	@param		optional string tagPath
	  *	@returns	true if successful, false otherwise
	  */
	function setTagContent($content, $tagPath = "") {
		if($tagPath == "")
			$tagPath = $this->tag->getTagName();
		$success = true;
		$tags = explode($this->pathSeparator, $tagPath);
		if($this->tag->getTagName() == $tags[0]) {
			if(sizeof($tags) == 1) {
				//$this->nodes = array(new XMLLeaf($content));
				$this->removeAllNodes();
				$this->addXMLLeaf(new XMLLeaf($content));
			}
			else {
				$nodeTagFound = false;
				reset($this->nodes);
				$arrKeys = array_keys($this->nodes);
				for($index = 0; $index < count($arrKeys); $index ++) {
					$node =& $this->nodes[$arrKeys[$index]];
					if(strtolower(get_class($node)) == "xmlbranch")
						if($node->tag->getTagName() == $tags[1]) {
							$newTagPath = implode($this->pathSeparator, array_slice($tags, 1));
							$success = $node->setTagContent($content, $newTagPath);
							$nodeTagFound = true;
						}
				}
				if(!$nodeTagFound) {
					$branch = new XMLBranch();
					$branch->setTag(new Tag($tags[1]));
					$newTagPath = implode($this->pathSeparator, array_slice($tags, 1));
					$branch->setTagContent($content, $newTagPath);
					$this->addXMLBranch($branch);
				}
			}
		}
		return $success;
	}

}

import("org.active-link.xml.XMLBranch");
import("org.active-link.xml.XMLLeaf");

?>
