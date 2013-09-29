<?php
// $Header: /cvsroot/html2ps/css.border.width.inc.php,v 1.4 2007/02/04 17:08:19 Konstantin Exp $

require_once(HTML2PS_DIR.'value.border.width.class.php');

class CSSBorderWidth extends CSSSubProperty {
  var $_defaultValue;

  function CSSBorderWidth(&$owner) {
    $this->CSSSubProperty($owner);

    $this->_defaultValue = new BorderWidth(Value::fromData(0, UNIT_PT),
                                           Value::fromData(0, UNIT_PT),
                                           Value::fromData(0, UNIT_PT),
                                           Value::fromData(0, UNIT_PT));
  }

  function setValue(&$owner_value, &$value) {
    if ($value != CSS_PROPERTY_INHERIT) {
      $owner_value->top->width    = $value->top;
      $owner_value->right->width  = $value->right;
      $owner_value->bottom->width = $value->bottom;
      $owner_value->left->width   = $value->left;
    } else {
      $owner_value->top->width    = CSS_PROPERTY_INHERIT;
      $owner_value->right->width  = CSS_PROPERTY_INHERIT;
      $owner_value->bottom->width = CSS_PROPERTY_INHERIT;
      $owner_value->left->width   = CSS_PROPERTY_INHERIT;
    };
  }

  function getValue(&$owner_value) {
    return new BorderWidth($owner_value->top->width, 
                           $owner_value->right->width, 
                           $owner_value->bottom->width, 
                           $owner_value->left->width);
  }

  function getPropertyCode() {
    return CSS_BORDER_WIDTH;
  }

  function getPropertyName() {
    return 'border-width';
  }

  function default_value() {
    return $this->_defaultValue;
  }

  function parse_value($value) {
    switch (strtolower($value)) {
    case 'thin':
      return Value::fromString('1px');
    case 'medium':
      return Value::fromString('3px');
    case 'thick':
      return Value::fromString('5px');
    default:
      return Value::fromString($value);
    };
  }

  function parse_in($value) {
    $values = explode(' ', $value);

    switch (count($values)) {
    case 1:
      $v1 = $this->parse_value($values[0]);
      return array($v1, $v1, $v1, $v1);
    case 2:
      $v1 = $this->parse_value($values[0]);
      $v2 = $this->parse_value($values[1]);
      return array($v1, $v2, $v1, $v2);
    case 3:
      $v1 = $this->parse_value($values[0]);
      $v2 = $this->parse_value($values[1]);
      $v3 = $this->parse_value($values[2]);
      return array($v1, $v2, $v3, $v2);
    case 4:
      $v1 = $this->parse_value($values[0]);
      $v2 = $this->parse_value($values[1]);
      $v3 = $this->parse_value($values[2]);
      $v4 = $this->parse_value($values[3]);
      return array($v1, $v2, $v3, $v4);
    default:
      return $this->default_value();
    };   
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    $values = $this->parse_in($value);
    return new BorderWidth($values[0], 
                           $values[1],
                           $values[2],
                           $values[3]);
  }
}

?>