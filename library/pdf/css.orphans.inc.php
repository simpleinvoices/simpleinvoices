<?php

class CSSOrphans extends CSSPropertyHandler {
  function __construct() {
    parent::__construct(true, false);
  }

  function default_value() { 
    return 2; 
  }

  function parse($value) {
    return (int)$value;
  }

  function getPropertyCode() {
    return CSS_ORPHANS;
  }

  function getPropertyName() {
    return 'orphans';
  }
}

$css_orphans_inc_reg1 = new CSSOrphans();
CSS::register_css_property($css_orphans_inc_reg1);
