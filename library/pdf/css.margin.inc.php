<?php

class MarginSideValue {
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
    $len = strlen($data);
    $is_percentage = false;
    if ($len > 0) {
      $is_percentage = ($data{$len-1} === '%');
    };

    $value = new MarginSideValue;
    $value->value      = $data;
    $value->percentage = $is_percentage ? (int)($data) : null;
    $value->auto       = $data === 'auto';
    return $value;
  }

  function units2pt($base) {
    $this->value = units2pt($this->value, $base);
  }
}

class MarginValue {
  var $top;
  var $bottom;
  var $left;
  var $right;

  function copy() {
    $value = new MarginValue;
    $value->top    = $this->top->copy();
    $value->bottom = $this->bottom->copy();
    $value->left   = $this->left->copy();
    $value->right  = $this->right->copy();
    return $value;
  }

  function init($data) {
    $value = new MarginValue;
    $value->top    = MarginSideValue::init($data[0]);
    $value->right  = MarginSideValue::init($data[1]);
    $value->bottom = MarginSideValue::init($data[2]);
    $value->left   = MarginSideValue::init($data[3]);
    return $value;
  }

  function is_default() {
    return 
      $this->left->is_default() &&
      $this->right->is_default() &&
      $this->top->is_default() &&
      $this->bottom->is_default();
  }

  function units2pt($base) {
    $this->top->units2pt($base);
    $this->bottom->units2pt($base);
    $this->left->units2pt($base);
    $this->right->units2pt($base);
  }
}

class CSSMargin extends CSSProperty {
  var $default_value;

  function CSSMargin() { 
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
      // We newer should get there, because 'margin' value can contain from 1 to 4 widths
      return "";
    };
  }

  function parse($string) {
    $value = MarginValue::init($this->parse_in($string));
    return $value;
  }
}
   
class CSSMarginTop extends CSSSubProperty {
  function parse($value) { return MarginSideValue::init($value); }
}

class CSSMarginRight extends CSSSubProperty {
  function parse($value) { return MarginSideValue::init($value); }
}

class CSSMarginLeft extends CSSSubProperty {
  function parse($value) { return MarginSideValue::init($value); }
}

class CSSMarginBottom extends CSSSubProperty {
  function parse($value) { return MarginSideValue::init($value); }
}

$mh = new CSSMargin;
register_css_property('margin'       ,$mh);
register_css_property('margin-left'  ,new CSSMarginLeft($mh, 'left'));
register_css_property('margin-right' ,new CSSMarginRight($mh, 'right'));
register_css_property('margin-top'   ,new CSSMarginTop($mh, 'top'));
register_css_property('margin-bottom',new CSSMarginBottom($mh, 'bottom'));

?>
