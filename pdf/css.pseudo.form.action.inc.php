<?php

class CSSPseudoFormAction extends CSSProperty {
  function CSSPseudoFormAction() { $this->CSSProperty(true, true); }

  function default_value() { return null; }

  function parse($value) { 
    return $value;
  }
}

register_css_property('-html2ps-form-action', new CSSPseudoFormAction);

?>