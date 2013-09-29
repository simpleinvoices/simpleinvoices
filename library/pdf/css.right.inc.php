<?php
// $Header: /cvsroot/html2ps/css.right.inc.php,v 1.6 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'value.right.php');

class CSSRight extends CSSPropertyHandler {
  function CSSRight() { 
    $this->CSSPropertyHandler(false, false); 
    $this->_autoValue = ValueRight::fromString('auto');
  }

  function _getAutoValue() {
    return $this->_autoValue->copy();
  }

  function default_value() { 
    return $this->_getAutoValue();
  }

  function parse($value) { 
    return ValueRight::fromString($value);
  }

  function getPropertyCode() {
    return CSS_RIGHT;
  }

  function getPropertyName() {
    return 'right';
  }
}

CSS::register_css_property(new CSSRight);

?>