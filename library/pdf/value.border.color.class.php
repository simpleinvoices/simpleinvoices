<?php

require_once(HTML2PS_DIR.'value.generic.php');

class BorderColor extends CSSValue {
  var $left;
  var $right;
  var $top;
  var $bottom;

  function &copy() {
    $value =& new BorderColor($this->top, $this->right, $this->bottom, $this->left);
    return $value;
  }

  function BorderColor($top, $right, $bottom, $left) {
    $this->left   = $left->copy();
    $this->right  = $right->copy();
    $this->top    = $top->copy();
    $this->bottom = $bottom->copy();
  }
}

?>