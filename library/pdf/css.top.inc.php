<?php
// $Header: /cvsroot/html2ps/css.top.inc.php,v 1.14 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'value.top.php');

class CSSTop extends CSSPropertyHandler {
  function __construct() {
    parent::__construct(false, false);
    $this->_autoValue = ValueTop::fromString('auto');
  }

  function _getAutoValue() {
    return $this->_autoValue->copy();
  }

  function default_value() { 
    return $this->_getAutoValue();
  }

  function getPropertyCode() {
    return CSS_TOP;
  }

  function getPropertyName() {
    return 'top';
  }

  function parse($value) { 
    return ValueTop::fromString($value);
  }
}

$css_top_inc_reg1 = new CSSTop();
CSS::register_css_property($css_top_inc_reg1);
