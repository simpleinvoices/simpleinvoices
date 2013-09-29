<?php
// $Header: /cvsroot/html2ps/css.border.bottom.color.inc.php,v 1.2 2006/11/16 03:32:56 Konstantin Exp $

class CSSBorderBottomColor extends CSSSubProperty {
  function CSSBorderBottomColor(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $owner_value->bottom->setColor($value);
  }

  function getValue(&$owner_value) {
    $value = $owner_value->bottom->color->copy();
    return $value;
  }

  function getPropertyCode() {
    return CSS_BORDER_BOTTOM_COLOR;
  }

  function getPropertyName() {
    return 'border-bottom-color';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return parse_color_declaration($value);
  }
}

?>