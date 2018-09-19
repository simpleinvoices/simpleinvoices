<?php

class CSSPseudoLinkTarget extends CSSPropertyHandler {
  function __construct() {
      parent::__construct(true, true);
  }

  function default_value() { return ""; }

  function is_external_link($value) {
    return (strlen($value) > 0 && $value{0} != "#");
  }

  function is_local_link($value) {
    return (strlen($value) > 0 && $value{0} == "#");
  }

  function parse($value, &$pipeline) { 
    // Keep local links (starting with sharp sign) as-is
    if (CSSPseudoLinkTarget::is_local_link($value)) { return $value; }

    $data = @parse_url($value);
    if (!isset($data['scheme']) || $data['scheme'] == "" || $data['scheme'] == "http") {
      return $pipeline->guess_url($value);
    } else {
      return $value;
    }
  }

  function getPropertyCode() {
    return CSS_HTML2PS_LINK_TARGET;
  }

  function getPropertyName() {
    return '-html2ps-link-target';
  }
}

$css_pseudo_link_target_inc_reg1 = new CSSPseudoLinkTarget();
CSS::register_css_property($css_pseudo_link_target_inc_reg1);
