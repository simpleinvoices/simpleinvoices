<?php

class CSSOrphans extends CSSPropertyHandler {
  function CSSOrphans() { 
    $this->CSSPropertyHandler(true, false); 
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

CSS::register_css_property(new CSSOrphans);

?>