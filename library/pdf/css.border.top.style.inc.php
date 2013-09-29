<?php
// $Header: /cvsroot/html2ps/css.border.top.style.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderTopStyle extends CSSSubProperty {
  function CSSBorderTopStyle(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $owner_value->top->style = $value;
  }

  function getValue(&$owner_value) {
    return $owner_value->top->style;
  }

  function getPropertyCode() {
    return CSS_BORDER_TOP_STYLE;
  }

  function getPropertyName() {
    return 'border-top-style';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return CSSBorderStyle::parse_style($value);
  }
}

?>