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

/**
  *	Tag class provides a base for parsing, modifying, outputting and creating XML tags
  *	@class		Tag
  *	@package	org.active-link.xml
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@see		XML
  */

class Tag {

	// protected variables
	var $tagStartOpen;
	var $tagStartClose;
	var $tagClose;
	var $tagEndOpen;
	var $tagEndClose;
	var $tagName;
	var $tagContent;
	var $tagAttributes;
	var $tagAttributeSeparator;
	var $tagAttributeSeparators;
	var $tagAttributeAssignment;
	var $tagAttributeValueQuote;
	var $FORMAT_NONE;
	var $FORMAT_INDENT;
	var $tagFormat;
	var $tagFormatIndentLevel;
	var $tagFormatEndTag;
	var $tagFormatNewLine = "\n";
	var $tagFormatIndent = "\t";

	/**
	  *	Constructor creates a tag object with the specified name and tag content
	  *	@method		Tag
	  *	@param		optional string name
	  *	@param		optional string content
	  *	@returns	none
	  */
	function Tag($name = "", $content = "") {
		$this->tagStartOpen = "<";
		$this->tagStartClose = ">";
		$this->tagClose = "/>";
		$this->tagEndOpen = "</";
		$this->tagEndClose = ">";
		$this->setTagName($name);
		$this->setTagContent($content);
		$this->tagAttributes = array();
		$this->tagAttributeSeparator = " ";
		$this->tagAttributeSeparators = array(" ", "\n", "\r", "\t");
		$this->tagAttributeAssignment = "=";
		$this->tagAttributeValueQuote = '"';
		$this->FORMAT_NONE = 0;
		$this->FORMAT_INDENT = 1;
		$this->tagFormat = $this->FORMAT_NONE;
		$this->tagFormatIndentLevel = 0;
		$this->tagFormatEndTag = false;
	}

	/**
	  *	Find out whether attribute exists
	  *	@method		attributeExists
	  *	@param		string attrName
	  *	@returns	true if attribute exists, false otherwise
	  */
	function attributeExists($attrName) {
		return array_key_exists($attrName, $this->tagAttributes);
	}

	/**
	  *	Get attribute value by its name
	  *	@method		getTagAttribute
	  *	@param		string attrName
	  *	@returns	string attribute value
	  */
	function getTagAttribute($attrName) {
		return $this->tagAttributes[$attrName];
	}

	/**
	  *	Get tag content string
	  *	@method		getTagContent
	  *	@returns	string tag content
	  */
	function getTagContent() {
		return $this->tagContent;
	}

	/**
	  *	Get tag name string
	  *	@method		getTagName
	  *	@returns	string tag name
	  */
	function getTagName() {
		return $this->tagName;
	}

	/**
	  *	Get complete tag string with its attributes and content
	  *	@method		getTagString
	  *	@returns	string tag string
	  */
	function getTagString() {
		$formatTagBegin = "";
		$formatTagEnd = "";
		$formatContent = "";
		if($this->tagFormat == $this->FORMAT_INDENT) {
			if($this->tagFormatIndentLevel > 0)
				$formatTagBegin = $this->tagFormatNewLine . str_repeat($this->tagFormatIndent, $this->tagFormatIndentLevel);
			if($this->tagFormatEndTag)
				$formatTagEnd = $this->tagFormatNewLine . str_repeat($this->tagFormatIndent, $this->tagFormatIndentLevel);
		}
		$tagString = $formatTagBegin . $this->getTagStringBegin() . $formatContent . $this->tagContent . $formatTagEnd . $this->getTagStringEnd();
		return $tagString;
	}

	/**
	  *	Get beginning of the tag string, i.e. its name attributes up until tag contents
	  *	@method		getTagStringBegin
	  *	@returns	string beginning of the tag string
	  */
	function getTagStringBegin() {
		$tagString = "";
		if($this->tagName != "") {
			$tagString .= $this->tagStartOpen . $this->tagName;
			foreach($this->tagAttributes as $attrName => $attrValue) {
				$tagString .= $this->tagAttributeSeparator . $attrName . $this->tagAttributeAssignment . $this->tagAttributeValueQuote . $attrValue . $this->tagAttributeValueQuote;
			}
			if($this->tagContent == "")
				$tagString .= $this->tagAttributeSeparator . $this->tagClose;
			else
				$tagString .= $this->tagStartClose;
		}
		return $tagString;
	}

	/**
	  *	Get ending of the tag string, i.e. its closing tag
	  *	@method		getTagStringEnd
	  *	@returns	string close tag if tag is not short-handed, empty string otherwise
	  */
	function getTagStringEnd() {
		$tagString = "";
		if($this->tagName != "" && $this->tagContent != "")
			$tagString .= $this->tagEndOpen . $this->tagName . $this->tagEndClose;
		return $tagString;
	}

	/**
	  *	Remove all tag attributes
	  *	@method		removeAllAttributes
	  *	@returns	none
	  */
	function removeAllAttributes() {
		$this->tagAttributes = array();
	}

	/**
	  *	Remove a tag attribute by its name
	  *	@method		removeAttribute
	  *	@returns	none
	  */
	function removeAttribute($attrName) {
		unset($this->tagAttributes[$attrName]);
	}

	/**
	  *	Reset the tag object - set name, content to empty strings, and reset all attributes
	  *	@method		resetTag
	  *	@returns	none
	  */
	function resetTag() {
		$this->setTagName("");
		$this->setTagContent("");
		$this->removeAllAttributes();
	}

	/**
	  *	Create or modify an existing attribute by supplying attribute name and value
	  *	@method		setAttribute
	  *	@param		string attrName
	  *	@param		string attrValue
	  *	@returns	none
	  */
	function setAttribute($attrName, $attrValue) {
		$this->tagAttributes[$attrName] = $attrValue;
	}

	/**
	  *	Set contents of the tag
	  *	@method		setTagContent
	  *	@param		string content
	  *	@returns	none
	  */
	function setTagContent($content) {
		$this->tagContent = $content;
	}

	/**
	  *	Set tag formatting option by specifying tagFormat to 0 (none), or 1 (indented)
	  *	@method		setTagFormat
	  *	@param		int tagFormat
	  *	@param		optional int tagFormatIndentLevel
	  *	@returns	none
	  */
	function setTagFormat($tagFormat, $tagFormatIndentLevel = 0) {
		$this->tagFormat = $tagFormat;
		$this->tagFormatIndentLevel = $tagFormatIndentLevel;
	}

	/**
	  *	Set whether closing of the tag should be formatted or not
	  *	@method		setTagFormatEndTag
	  *	@param		optional boolean formatEndTag
	  *	@returns	none
	  */
	function setTagFormatEndTag($formatEndTag = true) {
		$this->tagFormatEndTag = $formatEndTag;
	}

	/**
	  *	Parse a string containing a tag into the tag object, this will parse the first tag found
	  *	@method		setTagFromString
	  *	@param		string tagString
	  *	@returns	array array of [0]=>index of the beginning of the tag, [1]=>index where tag ended
	  */
	function setTagFromString($tagString) {
		$i = 0;
		$j = 0;
		$tagStartOpen = $tagStartClose = $tagNameStart = $tagNameEnd = $tagContentStart = $tagContentEnd = $tagEndOpen = $tagEndClose = 0;
		$tagName = $tagContent = "";
		$tagShort = false;
		$tagAttributes = array();
		$success = true;
		$tagFound = false;
		while(!$tagFound && $i < strlen($tagString)) {
			// look for start tag character
			$i = strpos($tagString, $this->tagStartOpen, $i);
			if($i === false)
				break;
			// if tag name starts from alpha character we found the tag
			if(ctype_alpha(substr($tagString, $i + 1, 1)))
				$tagFound = true;
			// else continue searching
			else
				$i ++;
		}
		// if no tag found set success to false
		if(!$tagFound)
			$success = false;
		// if so far so good continue with found tag name
		if($success) {
			$tagStartOpen = $i;
			$tagNameStart = $i + 1;
			// search where tag name would end
			// search for a space separator to account for attributes
			$separatorPos = array();
			for($counter = 0; $counter < count($this->tagAttributeSeparators); $counter ++) {
				$separatorPosTemp = strpos($tagString, $this->tagAttributeSeparators[$counter], $tagStartOpen);
				if($separatorPosTemp !== false)
					$separatorPos[] = $separatorPosTemp;
			}
			//$i = strpos($tagString, $this->tagAttributeSeparator, $tagStartOpen);
			if(count($separatorPos) > 0)
				$i = min($separatorPos);
			else
				$i = false;
			// search for tag close character
			$j = strpos($tagString, $this->tagStartClose, $tagStartOpen);
			// search for short tag (no content)
			$k = strpos($tagString, $this->tagClose, $tagStartOpen);
			// if tag close character is not found then no tag exists, set success to false
			if($j === false)
				$success = false;
			// if tag short close found before tag close, then tag is short
			if($k !== false && $k < $j)
				$tagShort = true;
		}
		// if so far so good set tag name correctly
		if($success) {
			// if space separator not found or it is found after the tag close char
			if($i === false || $i > $j) {
				if($tagShort)
					$tagNameEnd = $k;
				else
					$tagNameEnd = $j;
				$tagStartClose = $j;
			}
			// else if tag attributes exist
			else {
				$tagNameEnd = $i;
				$tagStartClose = $j;
				// parse attributes
				$tagAttributesStart = $i + strlen($this->tagAttributeSeparator);
				$attrString = trim(substr($tagString, $tagAttributesStart, $j - $tagAttributesStart));
				$attrArray = explode($this->tagAttributeValueQuote, $attrString);
				$attrCounter = 0;
				while($attrCounter < count($attrArray) - 1) {
					$attributeName = trim(str_replace($this->tagAttributeAssignment, "", $attrArray[$attrCounter]));
					$attributeValue = $attrArray[$attrCounter + 1];
					$tagAttributes[$attributeName] = $attributeValue;
					$attrCounter += 2;
				}
			}
			$tagName = rtrim(substr($tagString, $tagNameStart, $tagNameEnd - $tagNameStart));
			if(!$tagShort) {
				$tagContentStart = $tagStartClose + 1;
				// look for ending of the tag after tag content
				$j = $tagContentStart;
				$tagCloseFound = false;
				// while loop will find the k-th tag close
				// start with one since we have one tag open
				$k = 1;
				while(!$tagCloseFound && $success) {
					// find k-th tag close from j
					$n = $j - 1;
					for($skip = 0; $skip < $k; $skip ++) {
						$n ++;
						$tempPos = strpos($tagString, $this->tagEndOpen . $tagName . $this->tagEndClose, $n);
						if($tempPos !== false)
							$n = $tempPos;
						else {
							$success = false;
							break;
						}
					}
					// if success, find number of tag opens before the tag close
					$k = 0;
					if($success) {
						$tempString = substr($tagString, $j, $n - $j);
						$tempNewPos = 0;
						do {
							$tempPos = strpos($tempString, $this->tagStartOpen . $tagName, $tempNewPos);
							if($tempPos !== false) {
								$tempPosChar = substr($tempString, $tempPos + strlen($this->tagStartOpen . $tagName), 1);
								$tagEndArray = $this->tagAttributeSeparators;
								$tagEndArray[] = $this->tagEndClose;
								$tempPosTagEnded = array_search($tempPosChar, $tagEndArray);
								if($tempPosTagEnded !== false && $tempPosTagEnded !== NULL) {
									$tempStartClose = strpos($tempString, $this->tagStartClose, $tempPos);
									$tempStartShortClose = strpos($tempString, $this->tagClose, $tempPos);
									// if open tag found increase counter
									if($tempStartClose !== false && ($tempStartShortClose === false || $tempStartClose < $tempStartShortClose))
										$k ++;
									$tempNewPos = $tempPos + strlen($this->tagStartOpen . $tagName);
								}
								else
									$tempNewPos = $tempPos + strlen($this->tagStartOpen . $tagName);
							}
						} while($tempPos !== false);
					}
					// if no tags opened we found the tag close
					if($k == 0)
						$tagCloseFound = true;
					// else set new j
					else {
						$j = $n + strlen($this->tagEndOpen . $tagName . $this->tagEndClose);
					}
				}
				if($tagCloseFound)
					$i = $n;
				else
					$success = false;
			}
		}
		// if so far so good, then we have everything we need! set the object
		if($success) {
			if(!$tagShort) {
				$tagContentEnd = $i;
				$tagContent = substr($tagString, $tagContentStart, $tagContentEnd - $tagContentStart);
				$tagEndOpen = $i;
				$tagEndClose = $tagEndOpen + strlen($this->tagEndOpen . $tagName . $this->tagEndClose);
			}
			else
				$tagEndClose = $tagStartClose + strlen($this->tagStartClose);
			$this->setTagName($tagName);
			$this->setTagContent($tagContent);
			$this->tagAttributes = $tagAttributes;
		}
		if($success)
			return array($tagStartOpen, $tagEndClose);
		else
			return false;
	}

	/**
	  *	Set tag name
	  *	@method		setTagName
	  *	@param		string name
	  *	@returns	none
	  */
	function setTagName($name) {
		$this->tagName = $name;
	}

}

?>
