<?php

class CSSWidows extends CSSPropertyHandler {
  function CSSWidows() { 
    $this->CSSPropertyHandler(true, false); 
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

CSS::register_css_property(new CSSWidows);

?>