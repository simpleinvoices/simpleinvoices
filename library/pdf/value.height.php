<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueHeight extends CSSValuePercentage {
  function fromString($value) {
    $fromString = new ValueHeight();
    return CSSValuePercentage::_fromString($value, $fromString);
  }

  function &copy() {
    $copyVar = new ValueHeight();
    $value =& parent::_copy($copyVar);
    return $value;
  }
}
