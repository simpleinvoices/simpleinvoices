<?php
// $Header: /cvsroot/html2ps/css.overflow.inc.php,v 1.6 2006/04/16 16:54:57 Konstantin Exp $

define('OVERFLOW_VISIBLE',0);
define('OVERFLOW_HIDDEN',1);

class CSSOverflow extends CSSProperty {
  function CSSOverflow() { $this->CSSProperty(false, false); }

  function default_value() { return OVERFLOW_VISIBLE; }

  function parse($value) {
    switch ($value) {
    case 'hidden':
    case 'scroll':
    case 'auto':
      return OVERFLOW_HIDDEN;
    case 'visible':
    default:
      return OVERFLOW_VISIBLE;
    };
  }

  function pdf() {
    return $this->get();
  }
}

register_css_property('overflow', new CSSOverflow);

?>
