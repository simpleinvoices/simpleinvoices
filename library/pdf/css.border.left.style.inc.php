<?php
// $Header: /cvsroot/html2ps/css.border.left.style.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderLeftStyle extends CSSSubProperty {
  function CSSBorderLeftStyle(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $owner_value->left->style = $value;
  }

  function getValue(&$owner_value) {
    return $owner_value->left->style;
  }

  function getPropertyCode() {
    return CSS_BORDER_LEFT_STYLE;
  }

  function getPropertyName() {
    return 'border-left-style';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return CSSBorderStyle::parse_style($value);
  }
}

?>