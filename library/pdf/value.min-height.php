<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueMinHeight extends CSSValuePercentage {
  function fromString($value) {
    $valueMinHeight = new ValueMinHeight();
    return CSSValuePercentage::_fromString($value, $valueMinHeight);
  }

  function &copy() {
    $valueMinHeight = new ValueMinHeight();
    $value =& parent::_copy($valueMinHeight);
    return $value;
  }
}
