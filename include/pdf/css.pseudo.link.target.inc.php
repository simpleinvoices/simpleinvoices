<?php

class CSSPseudoLinkTarget extends CSSProperty {
  function CSSPseudoLinkTarget() { $this->CSSProperty(true, true); }

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
    };
  }
}

register_css_property('-html2ps-link-target', new CSSPseudoLinkTarget);

?>