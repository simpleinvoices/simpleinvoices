<?php

require_once(HTML2PS_DIR.'value.generic.php');

class BorderStyle extends CSSValue {
  var $left;
  var $right;
  var $top;
  var $bottom;

  function &copy() {
    $value =& new BorderStyle($this->top, $this->right, $this->bottom, $this->left);
    return $value;
  }

  function BorderStyle($top, $right, $bottom, $left) {
    $this->left   = $left;
    $this->right  = $right;
    $this->top    = $top;
    $this->bottom = $bottom;
  }
}

?>