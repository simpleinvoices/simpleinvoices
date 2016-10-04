<?php
// $Header: /cvsroot/html2ps/css.border.bottom.style.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderBottomStyle extends CSSSubProperty {
  function CSSBorderBottomStyle(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $owner_value->bottom->style = $value;
  }

  function getValue(&$owner_value) {
    return $owner_value->bottom->style;
  }

  function getPropertyCode() {
    return CSS_BORDER_BOTTOM_STYLE;
  }

  function getPropertyName() {
    return 'border-bottom-style';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return CSSBorderStyle::parse_style($value);
  }
}

?>