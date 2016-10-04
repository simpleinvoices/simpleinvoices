<?php
// $Header: /cvsroot/html2ps/css.left.inc.php,v 1.9 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'value.left.php');

class CSSLeft extends CSSPropertyHandler {
  function CSSLeft() { 
    $this->CSSPropertyHandler(false, false); 
    $this->_autoValue = ValueLeft::fromString('auto');
  }

  function _getAutoValue() {
    return $this->_autoValue->copy();
  }

  function default_value() { 
    return $this->_getAutoValue();
  }

  function parse($value) { 
    return ValueLeft::fromString($value);
  }

  function getPropertyCode() {
    return CSS_LEFT;
  }

  function getPropertyName() {
    return 'left';
  }
}

CSS::register_css_property(new CSSLeft);

?>