<?php

class CSSWidows extends CSSPropertyHandler {
  function __construct() {
      parent::__construct(true, false);
  }

  function default_value() { return 2; }

  function parse($value) {
    return (int)$value;
  }

  function getPropertyCode() {
    return CSS_WIDOWS;
  }

  function getPropertyName() {
    return 'widows';
  }
}

$css_widows_inc_reg1 = new CSSWidows();
CSS::register_css_property($css_widows_inc_reg1);
