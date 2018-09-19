<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueMaxHeight extends CSSValuePercentage {
  function fromString($value) {
    $valueMaxHeight = new ValueMaxHeight();
    return CSSValuePercentage::_fromString($value, $valueMaxHeight);
  }

  function &copy() {
    $valueMaxHeight = new ValueMaxHeight();
    $value =& parent::_copy($valueMaxHeight);
    return $value;
  }
}
