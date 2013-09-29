<?php
// $Header: /cvsroot/html2ps/css.list-style-type.inc.php,v 1.13 2006/09/07 18:38:14 Konstantin Exp $

// FIXME: supported only partially
define('LST_NONE',0);
define('LST_DISC',1);
define('LST_CIRCLE',2);
define('LST_SQUARE',3);
define('LST_DECIMAL',4);
define('LST_DECIMAL_LEADING_ZERO',5);
define('LST_LOWER_ROMAN',6);
define('LST_UPPER_ROMAN',7);
define('LST_LOWER_LATIN',8);
define('LST_UPPER_LATIN',9);

class CSSListStyleType extends CSSSubFieldProperty {
  // CSS 2.1: default value for list-style-type is 'disc'
  function default_value() { return LST_DISC; }

  function parse($value) {
    if (preg_match('/\bnone\b/',$value))    { return LST_NONE; };
    if (preg_match('/\bdisc\b/',$value))    { return LST_DISC; };
    if (preg_match('/\bcircle\b/',$value))  { return LST_CIRCLE; };
    if (preg_match('/\bsquare\b/',$value))  { return LST_SQUARE; };
    if (preg_match('/\bdecimal-leading-zero\b/',$value)) { return LST_DECIMAL_LEADING_ZERO; }
    if (preg_match('/\bdecimal\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\blower-roman\b/',$value)) { return LST_LOWER_ROMAN; }
    if (preg_match('/\bupper-roman\b/',$value)) { return LST_UPPER_ROMAN; }
    if (preg_match('/\blower-latin\b/',$value)) { return LST_LOWER_LATIN; }
    if (preg_match('/\bupper-latin\b/',$value)) { return LST_UPPER_LATIN; }
    if (preg_match('/\blower-alpha\b/',$value)) { return LST_LOWER_LATIN; }
    if (preg_match('/\bupper-alpha\b/',$value)) { return LST_UPPER_LATIN; }

    // Unsupported CSS values:
    // According to CSS 2.1 specs 12.6.2, a user agent that does not recognize a numbering system should use 'decimal'.
    if (preg_match('/\bhebrew\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\bgeorgian\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\barmenian\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\bcjk-ideographic\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\bhiragana\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\bkarakana\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\bhiragana-iroha\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\bkatakana-iroha\b/',$value)) { return LST_DECIMAL; };
    if (preg_match('/\blower-greek\b/',$value)) { return LST_DECIMAL; };

    return null;
  }

  function format_number($type,$num) {
    // NOTE: according to CSS 2.1 specs 12.6.2, "This specification does not define how alphabetic systems wrap 
    // at the end of the alphabet. For instance, after 26 list items, 'lower-latin' rendering is undefined. 
    // Therefore, for long lists, we recommend that authors specify true numbers.".
    // In our case we chose just to wrap over the beginning of alphabet (so, 'a' will again appear after 'z')
    //
    // Also, we're hoping that encoding we're using contains character codes in alphabetic order
    // It is true for ASCII, but there's some other crazy encodings... :-)
    //
    // Also, I really do not understand _WHY_ PHP is spewing a lot of notice messages complaining about 
    // undefined constants if I'm using the equivalent 'switch' construct instead of 'if'
    switch ($type) {
    case LST_DECIMAL:
      return $num; 
    case LST_DECIMAL_LEADING_ZERO:
      return sprintf("%02d",$num); 
    case LST_LOWER_LATIN:
      return chr(ord('a')+($num-1) % 26); 
    case LST_UPPER_LATIN:
      return chr(ord('A')+($num-1) % 26); 
    case LST_LOWER_ROMAN:
      return strtolower(arabic_to_roman($num)); 
    case LST_UPPER_ROMAN:
      return arabic_to_roman($num); 
    default:
      return "";
    }
  }

  function getPropertyCode() {
    return CSS_LIST_STYLE_TYPE;
  }

  function getPropertyName() {
    return 'list-style-type';
  }
}

?>