<?php

class CSSPseudoFormAction extends CSSPropertyHandler {
  function __construct() {
      parent::__construct(true, true);
  }

  function default_value() { return null; }

  function parse($value) { 
    return $value;
  }

  function getPropertyCode() {
    return CSS_HTML2PS_FORM_ACTION;
  }

  function getPropertyName() {
    return '-html2ps-form-action';
  }
}

$css_pseudo_form_action_inc_reg1 = new CSSPseudoFormAction();
CSS::register_css_property($css_pseudo_form_action_inc_reg1);
