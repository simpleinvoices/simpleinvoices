<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueBottom extends CSSValuePercentage {
  function fromString($value) {
    $valueBottom = new ValueBottom();
    return CSSValuePercentage::_fromString($value, $valueBottom);
  }

  function &copy() {
    $valueBottom = new ValueBottom();
    $value =& parent::_copy($valueBottom);
    return $value;
  }
}
