<?php
// $Header: /cvsroot/html2ps/css.white-space.inc.php,v 1.5 2006/04/16 16:54:57 Konstantin Exp $

define('WHITESPACE_NORMAL',0);
define('WHITESPACE_PRE',1);
define('WHITESPACE_NOWRAP',2);

class CSSWhiteSpace extends CSSProperty {
  function CSSWhiteSpace() { $this->CSSProperty(true, true); }

  function default_value() { return WHITESPACE_NORMAL; }

  function parse($value) {
    switch ($value) {
    case "normal": 
      return WHITESPACE_NORMAL;
    case "pre":
      return WHITESPACE_PRE;
    case "nowrap":
      return WHITESPACE_NOWRAP;
    default:
      return WHITESPACE_NORMAL;
    }
  }      
}

register_css_property('white-space', new CSSWhiteSpace);
  
?>