<?php
// $Header: /cvsroot/html2ps/css.parse.inc.php,v 1.21 2006/03/19 09:25:36 Konstantin Exp $

define("SELECTOR_CLASS_REGEXP","[\w\d_-]+");
define("SELECTOR_ID_REGEXP","[\w\d_-]+");
define("SELECTOR_ATTR_REGEXP","[\w]+");
define("SELECTOR_ATTR_VALUE_REGEXP","([\w]+)=['\"]?([\w]+)['\"]?");
define("SELECTOR_ATTR_VALUE_WORD_REGEXP" ,"([\w]+)~=['\"]?([\w]+)['\"]?");

// Parse the 'style' attribute value of current node\
//
function parse_style_attr($psdata, $root, &$pipeline) {
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
  $rule->apply($root, $pipeline);
}

function parse_style_node($root, &$pipeline) {
  global $g_stylesheet_title;

  // Check if this style node have 'media' attribute 
  // and if we're using this media;
  //
  // Note that, according to the HTML 4.01 p.14.2.3 
  // This attribute specifies the intended destination medium for style information. 
  // It may be a single media descriptor or a comma-separated list.
  // The default value for this attribute is "screen".
  //
  $media_list = array("screen");
  if ($root->has_attribute("media")) {

    // Note that there may be whitespace symbols around commas, so we should not just use 'explode' function
    //
    $media_list = preg_split("/\s*,\s*/",trim($root->get_attribute("media")));
  };

  if (!is_allowed_media($media_list)) { return; };

  if ($g_stylesheet_title === "") {
    $g_stylesheet_title = $root->get_attribute("title");
  };

  if (!$root->has_attribute("title") || $root->get_attribute("title") === $g_stylesheet_title) {
    /**
     * Check if current node is empty (then, we don't need to parse its contents)
     */
    $content = trim($root->get_content());
    if ($content != "") {
      parse_css($content, $pipeline);
    };
  };
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

function parse_css_import($import, &$pipeline) {
  if (preg_match("/@import\s+[\"'](.*)[\"'];/",$import, $matches)) {
    // @import "<url>"
    css_import(trim($matches[1]), $pipeline);
  } elseif (preg_match("/@import\s+url\((.*)\);/",$import, $matches)) {
    // @import url()
    css_import(trim(css_remove_value_quotes($matches[1])), $pipeline);
  } elseif (preg_match("/@import\s+(.*);/",$import, $matches)) {
    // @import <url>
    css_import(trim(css_remove_value_quotes($matches[1])), $pipeline);
  };
}

function parse_css_property($property, &$pipeline) {
  /**
   * Check for !important declaration; if it is present, remove the "!important"
   * substring from declaration text.
   *
   * @todo: store flag indicating that this property is important in returned data 
   */
  if (preg_match("/^(.*)!important/", $property, $matches)) {
    $property = $matches[1];
  };
  
  if (preg_match("/^(.*?)\s*:\s*(.*)/",$property, $matches)) {
    return array(
                 strtolower(trim($matches[1])) => trim($matches[2])
                 );
  } elseif (preg_match("/@import\s+\"(.*)\";/",$property, $matches)) {
    // @import "<url>"
    css_import(trim($matches[1]), $pipeline);
  } elseif (preg_match("/@import\s+url\((.*)\);/",$property, $matches)) {
    // @import url()
    css_import(trim($matches[1]), $pipeline);
  } elseif (preg_match("/@import\s+(.*);/",$property, $matches)) {
    // @import <url>
    css_import(trim($matches[1]), $pipeline);
  } else {
    return array();
  };
}

function parse_css_properties($raw_properties, &$pipeline) {
  $properties = split(";",$raw_properties);
  $results = array();
  foreach ($properties as $property) {
    $results = array_merge($results, parse_css_property(trim($property), $pipeline));
  };
  return $results;
}

function parse_css($css, &$pipeline, $baseindex = 0) {
  $allowed_media = implode("|",config_get_allowed_media());

  // remove the UTF8 byte-order mark from the beginning of the file (several high-order symbols at the beginning)
  $pos = 0;
  $len = strlen($css);
  while (ord($css{$pos}) > 127 && $pos < $len) { $pos ++; };
  $css = substr($css, $pos);

  // Process @media rules; 
  // basic syntax is:
  // @media <media>(,<media>)* { <rules> }
  //

  while (preg_match("/^(.*?)@media([^{]+){(.*)$/s",$css,$matches)) {
    $head  = $matches[1];
    $media = $matches[2];
    $rest  = $matches[3];

    // Process CSS rules placed before the first @media declaration - they should be applied to 
    // all media types
    //
    parse_css_media($head, $pipeline, $baseindex);

    // Extract the media content
    if (!preg_match("/^((?:[^{}]*{[^{}]*})*)[^{}]*\s*}(.*)$/s", $rest, $matches)) {
      die("CSS media syntax error\n");
    } else {
      $content = $matches[1];
      $tail    = $matches[2];
    };

    // Check if this media is to be processed
    if (preg_match("/".$allowed_media."/i", $media)) {
      parse_css_media($content, $pipeline, $baseindex);
    };

    // Process the rest of CSS file
    $css = $tail;
  };

  // The rest of CSS file belogs to common media, process it too
  parse_css_media($css, $pipeline, $baseindex);
}

function parse_css_media($css, &$pipeline, $baseindex = 0) {
  // Remove comments
  $css = preg_replace("#/\*.*?\*/#is","",$css);

  // Extract @import rules
  if ($num = preg_match_all("/@import[^;]+;/",$css, $matches, PREG_PATTERN_ORDER)) {
    for ($i=0; $i<$num; $i++) {
      parse_css_import($matches[0][$i], $pipeline);
    }
  };

  while (preg_match("/([^{}]*){(.*?)}(.*)/is", $css, $matches)) {
    // Drop extracted part
    $css = $matches[3];

    // Save extracted part
    $raw_selectors  = $matches[1];
    $raw_properties = $matches[2];

    $selectors  = parse_css_selectors($raw_selectors);

    $properties = parse_css_properties($raw_properties, $pipeline);

    foreach ($selectors as $selector) {
      // FIXME: this stuff definitely should be incapsulated
      global $g_css;
      global $g_css_index;
      $g_css_index++;
      array_push($g_css, array($selector, $properties, 
                               $pipeline->get_base_url(),
                               $g_css_index + $baseindex));
    };
  };
}
?>