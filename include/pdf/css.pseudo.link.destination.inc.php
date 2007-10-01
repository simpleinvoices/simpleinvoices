<?php

class CSSPseudoLinkDestination extends CSSProperty {
  function CSSPseudoLinkDestination() { $this->CSSProperty(false, false); }

  function default_value() { return ""; }

  function parse($value) { 
    return $value;
  }
}

register_css_property('-html2ps-link-destination', new CSSPseudoLinkDestination);

?>