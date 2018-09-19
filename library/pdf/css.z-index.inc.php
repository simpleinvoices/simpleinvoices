<?php

class CSSZIndex extends CSSPropertyHandler {
  function __construct() {
      parent::__construct(false, false);
  }

  function default_value() { return 0; }

  function parse($value) {
    if ($value === 'inherit') { 
      return CSS_PROPERTY_INHERIT;
    };

    return (int)$value;
  }

  function getPropertyCode() {
    return CSS_Z_INDEX;
  }

  function getPropertyName() {
    return 'z-index';
  }
}

$css_z_index_inc_reg1 = new CSSZIndex();
CSS::register_css_property($css_z_index_inc_reg1);
