<?php

require_once(HTML2PS_DIR.'value.generic.php');

/**
 * @version 1.0
 * @created 14-θών-2006 17:49:11
 */
class WidthConstraint extends CSSValue {
  var $_min_width;

  function WidthConstraint() {
    $this->_min_width = Value::fromData(0, UNIT_PT);
  }

  function apply($w, $pw) {
    $width = $this->_apply($w, $pw);
    $width = max($this->_min_width->getPoints(), $width);
    return $width;
  }

  function &copy() {
    $copy =& $this->_copy();

    if ($this->_min_width == CSS_PROPERTY_INHERIT) {
      $copy->_min_width = CSS_PROPERTY_INHERIT;
    } else {
      $copy->_min_width = $this->_min_width->copy();
    };

    return $copy;
  }

  function units2pt($base) {
    $this->_units2pt($base);
    $this->_min_width->units2pt($base);
  }

  function isNull() { 
    return false; 
  }

  function isFraction() {
    return false;
  }

  function isConstant() {
    return false;
  }
}
?>