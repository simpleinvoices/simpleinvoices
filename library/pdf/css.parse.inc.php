<?php
// $Header: /cvsroot/html2ps/css.parse.inc.php,v 1.28 2007/03/15 18:37:31 Konstantin Exp $

require_once(HTML2PS_DIR.'css.rules.page.inc.php');
require_once(HTML2PS_DIR.'css.property.collection.php');
require_once(HTML2PS_DIR.'css.parse.properties.php');

define("SELECTOR_CLASS_REGEXP","[\w\d_-]+");
define("SELECTOR_ID_REGEXP","[\w\d_-]+");
define("SELECTOR_ATTR_REGEXP","[\w]+");
define("SELECTOR_ATTR_VALUE_REGEXP","([\w]+)=['\"]?([\w]+)['\"]?");
define("SELECTOR_ATTR_VALUE_WORD_REGEXP" ,"([\w]+)~=['\"]?([\w]+)['\"]?");

// Parse the 'style' attribute value of current node\
//
function parse_style_attr($root, &$state, &$pipeline) {
  $style = $root->get_attribute("style");

  // Some "designers" (obviously lacking the brain and ability to read ) use such constructs:
  // 
  // <input maxLength=256 size=45 name=searchfor value="" style="{width:350px}">
  //
  // It is out of standard, as HTML 4.01 says:
  // 
  // The syntax of the value of the style attribute is determined by the default style sheet language. 
  // For example, for [[CSS2]] inline style, use the declaration block syntax described in section 4.1.8 
  // *(without curly brace delimiters)*
  //
  // but still parsed by many browsers; let's be compatible with these idiots - remove curly braces
  //
  $style = preg_replace("/^\s*{/","",$style);
  $style = preg_replace("/}\s*$/","",$style);

  $properties = parse_css_properties($style, $pipeline);

  $rule = new CSSRule(array(
                            array(SELECTOR_ANY),
                            $properties,
                            $pipeline->get_base_url(),
                            $root
                            ),
                      $pipeline);

  $rule->apply($root, $state, $pipeline);
}

// TODO: make a real parser instead of if-then-else mess
//
// Selector grammar (according to CSS 2.1, paragraph 5.1 & 5.2):
// Note that this particular grammar is not LL1, but still can be converter to 
// that form
//
// COMPOSITE_SELECTOR  ::= SELECTOR ("," SELECTOR)*
//
// SELECTOR            ::= SIMPLE_SELECTOR (COMBINATOR SIMPLE_SELECTOR)*
//
// COMBINATOR          ::= WHITESPACE* COMBINATOR_SYMBOL WHITESPACE*
// COMBINATOR_SYMBOL   ::= " " | ">" | "+"
//
// SIMPLE_SELECTOR     ::= TYPE_SELECTOR (ADDITIONAL_SELECTOR)*
// SIMPLE_SELECTOR     ::= UNIVERSAL_SELECTOR (ADDITIONAL_SELECTOR)*
// SIMPLE_SELECTOR     ::= (ADDITIONAL_SELECTOR)*
//
// CSS 2.1, p. 5.3: if the universal selector is not the only component of a simple selector, the "*" may be omitted
// SIMPLE_SELECTOR     ::= (ADDITIONAL_SELECTOR)* 
//
// TYPE_SELECTOR       ::= TAG_NAME
//
// UNIVERSAL_SELECTOR  ::= "*"
//
// ADDITIONAL_SELECTOR ::= ATTRIBUTE_SELECTOR | ID_SELECTOR | PSEUDOCLASS | CLASS_SELECTOR | PSEUDOELEMENT
// 
// ATTRIBUTE_SELECTOR  ::= "[" ATTRIBUTE_NAME "]" 
// ATTRIBUTE_SELECTOR  ::= "[" ATTRIBUTE_NAME "="  ATTR_VALUE "]" 
// ATTRIBUTE_SELECTOR  ::= "[" ATTRIBUTE_NAME "~=" ATTR_VALUE "]" 
// ATTRIBUTE_SELECTOR  ::= "[" ATTRIBUTE_NAME "|=" ATTR_VALUE "]" 
// 
// CLASS_SELECTOR      ::= "." CLASS_NAME
//
// ID_SELECTOR         ::= "#" ID_VALUE
//
// PSEUDOCLASS         ::= ":first-child"    | 
//                         ":link"           | 
//                         ":visited"        | // ignored in our case
//                         ":hover"          | // dynamic - ignored in our case
//                         ":active"         | // dynamic - ignored in our case
//                         ":focus"          | // dynamic - ignored in our case
//                         ":lang(" LANG ")" | // dynamic - ignored in our case
//
// PSEUDOELEMENT       ::= ":first-line"     |
//                         ":first-letter"   | 
//                         ":before"         | 
//                         ":after"          | 
//
// ATTR_VALUE          ::= IDENTIFIER | STRING
// CLASS_NAME          ::= INDETIFIER
// ID_VALUE            ::= IDENTIFIER
//
function parse_css_selector($raw_selector) {
  // Note a 'trim' call. Is is required as there could be leading/trailing spaces in $raw_selector
  //
  $raw_selector = strtolower(trim($raw_selector));

  // Direct Parent/child selectors (for example 'table > tr')
  if (preg_match("/^(\S.*)\s*>\s*([^\s]+)$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(
                                          parse_css_selector($matches[2]),
                                          array(SELECTOR_DIRECT_PARENT, 
                                                parse_css_selector($matches[1]))));
  }

  // Parent/child selectors (for example 'table td')
  if (preg_match("/^(\S.*)\s+([^\s]+)$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(
                                          parse_css_selector($matches[2]),
                                          array(SELECTOR_PARENT, 
                                                parse_css_selector($matches[1]))));
  }

  if (preg_match("/^(.+)\[(".SELECTOR_ATTR_REGEXP.")\]$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(
                                          parse_css_selector($matches[1]),
                                          array(SELECTOR_ATTR, $matches[2])));
  }

  if (preg_match("/^(.+)\[".SELECTOR_ATTR_VALUE_REGEXP."\]$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(
                                          parse_css_selector($matches[1]),
                                          array(SELECTOR_ATTR_VALUE, $matches[2], css_remove_value_quotes($matches[3]))));
  }

  if (preg_match("/^(.+)\[".SELECTOR_ATTR_VALUE_WORD_REGEXP."\]$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(
                                          parse_css_selector($matches[1]),
                                          array(SELECTOR_ATTR_VALUE_WORD, $matches[2], css_remove_value_quotes($matches[3]))));
  }

  // pseudoclasses & pseudoelements
  if (preg_match("/^([#\.\s\w_-]*):(\w+)$/", $raw_selector, $matches)) {
    if ($matches[1] === "") { $matches[1] = "*"; };

    switch($matches[2]) {
     case "lowlink":
      return array(SELECTOR_SEQUENCE, array(parse_css_selector($matches[1]), array(SELECTOR_PSEUDOCLASS_LINK_LOW_PRIORITY)));
     case "link":
      return array(SELECTOR_SEQUENCE, array(parse_css_selector($matches[1]), array(SELECTOR_PSEUDOCLASS_LINK)));
     case "before":
      return array(SELECTOR_SEQUENCE, array(parse_css_selector($matches[1]), array(SELECTOR_PSEUDOELEMENT_BEFORE)));
     case "after":
      return array(SELECTOR_SEQUENCE, array(parse_css_selector($matches[1]), array(SELECTOR_PSEUDOELEMENT_AFTER)));
    };
  };

  // :lang() pseudoclass
  if (preg_match("/^([#\.\s\w_-]+):lang\((\w+)\)$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(parse_css_selector($matches[1]), array(SELECTOR_LANGUAGE, $matches[2])));
  };

  if (preg_match("/^(\S+)(\.\S+)$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(parse_css_selector($matches[1]), parse_css_selector($matches[2])));
  };

  switch ($raw_selector{0}) {
  case '#':
    return array(SELECTOR_ID,    substr($raw_selector,1));
  case '.':
    return array(SELECTOR_CLASS, substr($raw_selector,1));
  };

  if (preg_match("/^(\w+)#(".SELECTOR_ID_REGEXP.")$/", $raw_selector, $matches)) {
    return array(SELECTOR_SEQUENCE, array(array(SELECTOR_ID, $matches[2]), array(SELECTOR_TAG, $matches[1])));
  };

  if ($raw_selector === "*") {
    return array(SELECTOR_ANY);
  };

  return array(SELECTOR_TAG,$raw_selector);
}

function parse_css_selectors($raw_selectors) {
  $offset = 0;
  $selectors = array();

  $selector_strings = explode(",",$raw_selectors);

  foreach ($selector_strings as $selector_string) {
    // See comment on SELECTOR_ANY regarding why this code is commented
    // Remove the '* html' string from the selector
    // $selector_string = preg_replace('/^\s*\*\s+html/','',$selector_string);

    $selector_string = trim($selector_string);

    // Support for non-valid CSS similar to: "selector1,selector2, {rules}"
    // In this case we'll get three selectors; last will be empty string

    if (!empty($selector_string)) {
      $selectors[] = parse_css_selector($selector_string);
    };
  };
  
  return $selectors;
}

// function &parse_css_property($property, &$pipeline) { 
//   if (preg_match("/^(.*?)\s*:\s*(.*)/",$property, $matches)) {
//     $name = strtolower(trim($matches[1]));
//     $code = CSS::word2code($name);
//     if (is_null($code)) { 
//       error_log(sprintf("Unsupported CSS property: '%s'", $name));
//       $null = null;
//       return $null;
//     };

//     $collection =& new CSSPropertyCollection();
//     $collection->addProperty(CSSPropertyDeclaration::create($code, trim($matches[2]), $pipeline));
//     return $collection;
//   } elseif (preg_match("/@import\s+\"(.*)\";/",$property, $matches)) {
//     // @import "<url>"
//     $collection =& css_import(trim($matches[1]), $pipeline);
//     return $collection;
//   } elseif (preg_match("/@import\s+url\((.*)\);/",$property, $matches)) {
//     // @import url()
//     $collection =& css_import(trim($matches[1]), $pipeline);
//     return $collection;
//   } elseif (preg_match("/@import\s+(.*);/",$property, $matches)) {
//     // @import <url>
//     $collection =& css_import(trim($matches[1]), $pipeline);
//     return $collection;
//   } else {
//     $collection =& new CSSPropertyCollection();
//     return $collection;
//   };
// }

?>