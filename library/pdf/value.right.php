<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueRight extends CSSValuePercentage {
  function fromString($value) {
    $valueRight = new ValueRight();
    return CSSValuePercentage::_fromString($value, $valueRight);
  }

  function &copy() {
    $valueRight = new ValueRight();
    $value =& parent::_copy($valueRight);
    return $value;
  }
}
