<?php
// $Header: /cvsroot/html2ps/css.clear.inc.php,v 1.9 2006/09/07 18:38:13 Konstantin Exp $

define('CLEAR_NONE',0);
define('CLEAR_LEFT',1);
define('CLEAR_RIGHT',2);
define('CLEAR_BOTH',3);

class CSSClear extends CSSPropertyStringSet {
  function __construct() {
    parent::__construct(false,
                        false,
                        array('inherit' => CSS_PROPERTY_INHERIT,
                              'left'    => CLEAR_LEFT,
                              'right'   => CLEAR_RIGHT,
                              'both'    => CLEAR_BOTH,
                              'none'    => CLEAR_NONE));
  }

  function default_value() { 
    return CLEAR_NONE; 
  }

  function getPropertyCode() {
    return CSS_CLEAR;
  }

  function getPropertyName() {
    return 'clear';
  }
}

$css_clear_inc_reg1 = new CSSClear();
CSS::register_css_property($css_clear_inc_reg1);
