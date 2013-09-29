<?php
// $Header: /cvsroot/html2ps/css.selectors.inc.php,v 1.12 2006/01/07 19:38:06 Konstantin Exp $

define('SELECTOR_ID'   ,1);
define('SELECTOR_CLASS',2);
define('SELECTOR_TAG'  ,3);
define('SELECTOR_TAG_CLASS',4);
define('SELECTOR_SEQUENCE', 5);
define('SELECTOR_PARENT', 6);         // TAG1 TAG2
define('SELECTOR_ATTR_VALUE', 7);
define('SELECTOR_PSEUDOCLASS_LINK', 8);
define('SELECTOR_ATTR', 9);
define('SELECTOR_DIRECT_PARENT', 10); // TAG1 > TAG2
define('SELECTOR_LANGUAGE', 11);      // SELECTOR:lang(..)

// Used for handling the body 'link' atttribute; this selector have no specificity at all
// we need to introduce this selector type as some ill-brained designers use constructs like:
//
// <html>
// <head><style type="text/css">a { color: red; }</style></head>
// <body link="#000000"><a href="test">test</a>
//
// in this case the CSS rule should have the higher priority; nevertheless, using the default selector rules
// we'd get find that 'link'-generated CSS rule is more important
//
define('SELECTOR_PSEUDOCLASS_LINK_LOW_PRIORITY', 12); 

// Used for hanling the following case:
//
// <head>
// <style>img { border: 0; }</style>
// </head>
// <body><a href=""><img height="10" width="10" src=""></a>
//
define('SELECTOR_PARENT_LOW_PRIORITY', 13); 

define('SELECTOR_PSEUDOELEMENT_BEFORE', 14);
define('SELECTOR_PSEUDOELEMENT_AFTER', 15);

// Note on SELECTOR_ANY:
// normally we should not process rules like 
// * html <some other selector> as they're IE specific and (according to CSS standard)
// should be never matched
define('SELECTOR_ANY', 16);

define('SELECTOR_ATTR_VALUE_WORD',17);

// CSS 2.1: 
// In CSS2, identifiers  (including element names, classes, and IDs in selectors) can contain only the characters [A-Za-z0-9] and 
// ISO 10646 characters 161 and higher, plus the hyphen (-); they cannot start with a hyphen or a digit. 
// They can also contain escaped characters and any ISO 10646 character as a numeric code (see next item). For instance, 
// the identifier "B&W?" may be written as "B\&W\?" or "B\26 W\3F".
//
// Any node can be marked by several space separated class names
//
function node_have_class($root, $target_class) {
  if (!$root->has_attribute('class')) { return false; };

  $classes = preg_split("/\s+/", strtolower($root->get_attribute('class')));

  foreach ($classes as $class) {
    if ($class == $target_class) { 
      return true; 
    };
  };

  return false;
};

function match_selector($selector, $root) {
  switch ($selector[0]) {
  case SELECTOR_TAG:
    if ($selector[1] == strtolower($root->tagname())) { return true; };
    break;
  case SELECTOR_ID:
    if ($selector[1] == strtolower($root->get_attribute('id'))) { return true; };
    break;
  case SELECTOR_CLASS:
    if (node_have_class($root, $selector[1])) { return true; }
    if ($selector[1] == strtolower($root->get_attribute('class'))) { return true; };
    break;
  case SELECTOR_TAG_CLASS:
    if ((node_have_class($root, $selector[2])) && 
        ($selector[1] == strtolower($root->tagname()))) { return true; };
    break;      
  case SELECTOR_SEQUENCE:
    foreach ($selector[1] as $subselector) {
      if (!match_selector($subselector, $root)) { return false; };
    };
    return true;
  case SELECTOR_PARENT:
  case SELECTOR_PARENT_LOW_PRIORITY:
    $node = $root->parent();

    while ($node && $node->node_type() == XML_ELEMENT_NODE) {
      if (match_selector($selector[1], $node)) { return true; };
      $node = $node->parent();
    };
    return false;
  case SELECTOR_DIRECT_PARENT:
    $node = $root->parent();
    if ($node && $node->node_type() == XML_ELEMENT_NODE) {
      if (match_selector($selector[1], $node)) { return true; };
    };
    return false;
  case SELECTOR_ATTR:
    $attr_name = $selector[1];
    return $root->has_attribute($attr_name);
  case SELECTOR_ATTR_VALUE:
    // Note that CSS 2.1 standard does not says strictly if attribute case 
    // is significiant: 
    // """
    // Attribute values must be identifiers or strings. The case-sensitivity of attribute names and 
    // values in selectors depends on the document language.
    // """
    // As we've met several problems with pages having INPUT type attributes in upper (or ewen worse - mixed!)
    // case, the following decision have been accepted: attribute values should not be case-sensitive

    $attr_name  = $selector[1];
    $attr_value = $selector[2];

    if (!$root->has_attribute($attr_name)) {
      return false;
    };
    return strtolower($root->get_attribute($attr_name)) == strtolower($attr_value);
  case SELECTOR_ATTR_VALUE_WORD:
    // Note that CSS 2.1 standard does not says strictly if attribute case 
    // is significiant: 
    // """
    // Attribute values must be identifiers or strings. The case-sensitivity of attribute names and 
    // values in selectors depends on the document language.
    // """
    // As we've met several problems with pages having INPUT type attributes in upper (or ewen worse - mixed!)
    // case, the following decision have been accepted: attribute values should not be case-sensitive

    $attr_name  = $selector[1];
    $attr_value = $selector[2];

    if (!$root->has_attribute($attr_name)) {
      return false;
    };

    $words = preg_split("/\s+/",$root->get_attribute($attr_name));
    foreach ($words as $word) {
      if (strtolower($word) == strtolower($attr_value)) { return true; };
    };
    return false;
  case SELECTOR_PSEUDOCLASS_LINK:
    return $root->tagname() == "a" && $root->has_attribute('href');
  case SELECTOR_PSEUDOCLASS_LINK_LOW_PRIORITY:
    return $root->tagname() == "a" && $root->has_attribute('href');
    
    // Note that :before and :after always match
  case SELECTOR_PSEUDOELEMENT_BEFORE:
    return true;
  case SELECTOR_PSEUDOELEMENT_AFTER:
    return true;

  case SELECTOR_LANGUAGE:
    // FIXME: determine the document language 
    return true;

  case SELECTOR_ANY:
    return true;
  };
  return false;
}

function css_selector_specificity($selector) {
  switch ($selector[0]) {
  case SELECTOR_ID: 
    return array(1,0,0);
  case SELECTOR_CLASS:
    return array(0,1,0);
  case SELECTOR_TAG:
    return array(0,0,1);
  case SELECTOR_TAG_CLASS:
    return array(0,1,1);
  case SELECTOR_SEQUENCE:
    $specificity = array(0,0,0);
    foreach ($selector[1] as $subselector) {
      $s = css_selector_specificity($subselector);
      $specificity = array($specificity[0]+$s[0],
                           $specificity[1]+$s[1],
                           $specificity[2]+$s[2]);
    }
    return $specificity;
  case SELECTOR_PARENT:
    return css_selector_specificity($selector[1]);
  case SELECTOR_PARENT_LOW_PRIORITY:
    return array(-1,-1,-1);
  case SELECTOR_DIRECT_PARENT:
    return css_selector_specificity($selector[1]);
  case SELECTOR_ATTR:
    return array(0,1,0);
  case SELECTOR_ATTR_VALUE:
    return array(0,1,0);
  case SELECTOR_ATTR_VALUE_WORD:
    return array(0,1,0);
  case SELECTOR_PSEUDOCLASS_LINK:
    return array(0,1,0);
  case SELECTOR_PSEUDOCLASS_LINK_LOW_PRIORITY:
    return array(0,0,0);
  case SELECTOR_PSEUDOELEMENT_BEFORE:
    return array(0,0,0);
  case SELECTOR_PSEUDOELEMENT_AFTER:
    return array(0,0,0);
  case SELECTOR_LANGUAGE:
    return array(0,1,0);
  case SELECTOR_ANY:
    return array(0,1,0);
  default:
    die("Bad selector while calculating selector specificity:".$selector[0]);
  }
}

// Just an abstraction wrapper for determining the selector type 
// from the selector-describing structure
//
function selector_get_type($selector) {
  return $selector[0];
};

?>