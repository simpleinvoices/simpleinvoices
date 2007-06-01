<?php
// $Header: /cvsroot/html2ps/css.list-style-position.inc.php,v 1.5 2006/04/16 16:54:57 Konstantin Exp $

define('LSP_OUTSIDE',0);
define('LSP_INSIDE',1);

class CSSListStylePosition extends CSSSubProperty {
  // CSS 2.1: default value for list-style-position is 'outside'
  function default_value() { return LSP_OUTSIDE; }

  function parse($value) {
    if (preg_match('/\binside\b/',$value)) {
      return LSP_INSIDE; 
    };

    if (preg_match('/\boutside\b/',$value)) { 
      return LSP_OUTSIDE; 
    };

    return null;
  }
}

?>