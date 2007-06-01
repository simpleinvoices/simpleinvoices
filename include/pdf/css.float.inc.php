<?php
// $Header: /cvsroot/html2ps/css.float.inc.php,v 1.6 2006/04/16 16:54:57 Konstantin Exp $

define('FLOAT_NONE',0);
define('FLOAT_LEFT',1);
define('FLOAT_RIGHT',2);

class CSSFloat extends CSSProperty {
  function CSSFloat() { $this->CSSProperty(false, false); }

  function default_value() { return FLOAT_NONE; }

  function parse($value) {
    // Convert value to lower case, as html allows values 
    // in both cases to be entered
    $value = strtolower($value);
    
    if ($value === 'left') { return FLOAT_LEFT; };
    if ($value === 'right') { return FLOAT_RIGHT; };
    return FLOAT_NONE;
  }
}

register_css_property('float', new CSSFloat);

?>