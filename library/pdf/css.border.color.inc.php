<?php
// $Header: /cvsroot/html2ps/css.border.color.inc.php,v 1.3 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'value.border.color.class.php');

class CSSBorderColor extends CSSSubProperty {
  var $_defaultValue;

  function CSSBorderColor(&$owner) {
    $this->CSSSubProperty($owner);

    $this->_defaultValue = new BorderColor(new Color(array(0,0,0), true),
                                           new Color(array(0,0,0), true),
                                           new Color(array(0,0,0), true),
                                           new Color(array(0,0,0), true));
  }

  function setValue(&$owner_value, &$value) {
    if ($value != CSS_PROPERTY_INHERIT) {
      $owner_value->top->setColor($value->top);
      $owner_value->right->setColor($value->right);
      $owner_value->bottom->setColor($value->bottom);
      $owner_value->left->setColor($value->left);
    } else {
      $owner_value->top->setColor(CSS_PROPERTY_INHERIT);
      $owner_value->right->setColor(CSS_PROPERTY_INHERIT);
      $owner_value->bottom->setColor(CSS_PROPERTY_INHERIT);
      $owner_value->left->setColor(CSS_PROPERTY_INHERIT);
    };
  }

  function getValue(&$owner_value) {
    return new BorderColor($owner_value->top->color, 
                           $owner_value->right->color, 
                           $owner_value->bottom->color, 
                           $owner_value->left->color);
  }

  function getPropertyCode() {
    return CSS_BORDER_COLOR;
  }

  function getPropertyName() {
    return 'border-color';
  }

  function default_value() {
    return $this->_defaultValue;
  }
  
  function parse_in($value) {
    $values = preg_split("/(?<![,(\s])\s+/ ",$value);

    switch (count($values)) {
    case 1:
      $v1 = parse_color_declaration($values[0]);
      return array($v1, $v1, $v1, $v1);
    case 2:
      $v1 = parse_color_declaration($values[0]);
      $v2 = parse_color_declaration($values[1]);
      return array($v1, $v2, $v1, $v2);
    case 3:
      $v1 = parse_color_declaration($values[0]);
      $v2 = parse_color_declaration($values[1]);
      $v3 = parse_color_declaration($values[2]);
      return array($v1, $v2, $v3, $v2);
    case 4:
      $v1 = parse_color_declaration($values[0]);
      $v2 = parse_color_declaration($values[1]);
      $v3 = parse_color_declaration($values[2]);
      $v4 = parse_color_declaration($values[3]);
      return array($v1, $v2, $v3, $v4);
    default:
      return $this->default_value();
    };   
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    $colors = $this->parse_in($value);

    return new BorderColor($colors[0], 
                           $colors[1],
                           $colors[2],
                           $colors[3]);
  }
}

?>