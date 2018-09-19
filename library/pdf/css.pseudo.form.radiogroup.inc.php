<?php

class CSSPseudoFormRadioGroup extends CSSPropertyHandler {
  function __construct() {
      parent::__construct(true, true);
  }

  function default_value() { 
    return null; 
  }

  function parse($value) { 
    return $value;
  }

  function getPropertyCode() {
    return CSS_HTML2PS_FORM_RADIOGROUP;
  }

  function getPropertyName() {
    return '-html2ps-form-radiogroup';
  }
}

$css_pseudo_form_rediogroup_inc_reg1 = new CSSPseudoFormRadioGroup();
CSS::register_css_property($css_pseudo_form_rediogroup_inc_reg1);
