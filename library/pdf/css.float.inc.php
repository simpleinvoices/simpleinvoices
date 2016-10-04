<?php
// $Header: /cvsroot/html2ps/css.float.inc.php,v 1.7 2006/07/09 09:07:44 Konstantin Exp $

define('FLOAT_NONE',0);
define('FLOAT_LEFT',1);
define('FLOAT_RIGHT',2);

class CSSFloat extends CSSPropertyStringSet {
  function CSSFloat() { 
    $this->CSSPropertyStringSet(false, 
                                false,
                                array('left'  => FLOAT_LEFT,
                                      'right' => FLOAT_RIGHT,
                                      'none'  => FLOAT_NONE)); 
  }

  function default_value() { 
    return FLOAT_NONE; 
  }

  function getPropertyCode() {
    return CSS_FLOAT;
  }

  function getPropertyName() {
    return 'float';
  }
}

CSS::register_css_property(new CSSFloat);

?>