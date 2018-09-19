<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueTop extends CSSValuePercentage {
  function fromString($value) {
    $valueTop = new ValueTop();
    return CSSValuePercentage::_fromString($value, $valueTop);
  }

  function &copy() {
    $valueTop = new ValueTop();
    $value =& parent::_copy($valueTop);
    return $value;
  }
}
