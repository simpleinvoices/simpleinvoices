<?php
// $Header: /cvsroot/html2ps/css.color.inc.php,v 1.13 2007/01/24 18:55:51 Konstantin Exp $

class CSSColor extends CSSPropertyHandler {
  function __construct() {
      parent::__construct(true, true);
  }

  function default_value() { 
    return new Color(array(0,0,0),false); 
  }

  function parse($value) {
    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    return parse_color_declaration($value);
  }

  function getPropertyCode() {
    return CSS_COLOR;
  }

  function getPropertyName() {
    return 'color';
  }
}

$css_color_inc_reg1 = new CSSColor();
CSS::register_css_property($css_color_inc_reg1);
