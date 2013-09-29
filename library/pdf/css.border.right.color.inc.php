<?php
// $Header: /cvsroot/html2ps/css.border.right.color.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderRightColor extends CSSSubProperty {
  function CSSBorderRightColor(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $owner_value->right->setColor($value);
  }

  function getValue(&$owner_value) {
    return $owner_value->right->color->copy();
  }

  function getPropertyCode() {
    return CSS_BORDER_RIGHT_COLOR;
  }

  function getPropertyName() {
    return 'border-right-color';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return parse_color_declaration($value);
  }
}

?>