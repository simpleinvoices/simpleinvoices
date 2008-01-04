<?php
// $Header: /cvsroot/html2ps/css.clear.inc.php,v 1.7 2006/04/16 16:54:56 Konstantin Exp $

define('CLEAR_NONE',0);
define('CLEAR_LEFT',1);
define('CLEAR_RIGHT',2);
define('CLEAR_BOTH',3);

class CSSClear extends CSSProperty {
  function CSSClear() { $this->CSSProperty(false, false); }

  function default_value() { return CLEAR_NONE; }

  function parse($value) {
    if ($value === 'left')  { return CLEAR_LEFT; };
    if ($value === 'right') { return CLEAR_RIGHT; };
    if ($value === 'both')  { return CLEAR_BOTH; };
    return CLEAR_NONE;
  }
}

register_css_property('clear', new CSSClear);

?>