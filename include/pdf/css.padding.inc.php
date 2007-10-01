<?php

class PaddingSideValue {
  var $value;
  var $auto;
  var $percentage;

  function copy() {
    $value = new MarginSideValue;
    $value->value      = $this->value;
    $value->auto       = $this->auto;
    $value->percentage = $this->percentage;
    return $value;
  }

  function is_default() {
    return 
      $this->value == 0 &&
      !$this->auto &&
      !$this->percentage;
  }

  function init($data) {
    $value = new PaddingSideValue;
    $value->value      = $data;
    $value->percentage = $data{strlen($data)-1} === '%' ? (int)($data) : null;
    $value->auto       = $data === 'auto';
    return $value;
  }

  function units2pt($base) {
    $this->value = units2pt($this->value, $base);
  }
}

class PaddingValue {
  var $top;
  var $bottom;
  var $left;
  var $right;

  function copy() {
    $value = new PaddingValue;
    $value->top    = $this->top->copy();
    $value->bottom = $this->bottom->copy();
    $value->left   = $this->left->copy();
    $value->right  = $this->right->copy();
    return $value;
  }

  function is_default() {
    return 
      $this->left->is_default() &&
      $this->right->is_default() &&
      $this->top->is_default() &&
      $this->bottom->is_default();
  }

  function init($data) {
    $value = new PaddingValue;
    $value->top    = PaddingSideValue::init($data[0]);
    $value->right  = PaddingSideValue::init($data[1]);
    $value->bottom = PaddingSideValue::init($data[2]);
    $value->left   = PaddingSideValue::init($data[3]);
    return $value;
  }

  function units2pt($base) {
    $this->top->units2pt($base);
    $this->bottom->units2pt($base);
    $this->left->units2pt($base);
    $this->right->units2pt($base);
  }
}

class CSSPadding extends CSSProperty {
  var $default_value;

  function CSSPadding() { 
    $this->default_value = $this->parse("0");
    $this->CSSProperty(false, false); 
  }

  function default_value() { return $this->default_value->copy(); }

  function parse_in($value) {
    $values = explode(" ",trim($value));
    switch (count($values)) {
    case 1:
      $v1 = $values[0];
      return array($v1, $v1, $v1, $v1);
    case 2:
      $v1 = $values[0];
      $v2 = $values[1];
      return array($v1, $v2, $v1, $v2);
    case 3:
      $v1 = $values[0];
      $v2 = $values[1];
      $v3 = $values[2];
      return array($v1, $v2, $v3, $v2);
    case 4:
      $v1 = $values[0];
      $v2 = $values[1];
      $v3 = $values[2];
      $v4 = $values[3];
      return array($v1, $v2, $v3, $v4);
    default:
      // We newer should get there, because 'padding' value can contain from 1 to 4 widths
      return "";
    };
  }

  function parse($string) {
    return PaddingValue::init($this->parse_in($string));
  }
}
   
class CSSPaddingTop extends CSSSubProperty {
  function parse($value) { return PaddingSideValue::init($value); }
}

class CSSPaddingRight extends CSSSubProperty {
  function parse($value) { return PaddingSideValue::init($value); }
}

class CSSPaddingLeft extends CSSSubProperty {
  function parse($value) { return PaddingSideValue::init($value); }
}

class CSSPaddingBottom extends CSSSubProperty {
  function parse($value) { return PaddingSideValue::init($value); }
}

$ph = new CSSPadding;
register_css_property('padding'       ,$ph);
register_css_property('padding-left'  ,new CSSPaddingLeft($ph,   'left'));
register_css_property('padding-right' ,new CSSPaddingRight($ph,  'right'));
register_css_property('padding-top'   ,new CSSPaddingTop($ph,    'top'));
register_css_property('padding-bottom',new CSSPaddingBottom($ph, 'bottom'));

?>
