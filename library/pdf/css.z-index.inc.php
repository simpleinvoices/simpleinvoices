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

CSS::register_css_property(new CSSZIndex);
