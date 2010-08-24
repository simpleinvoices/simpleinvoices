<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueBottom extends CSSValuePercentage {
  function fromString($value) {
    return CSSValuePercentage::_fromString($value, new ValueBottom);
  }

  function &copy() {
    $value =& parent::_copy(new ValueBottom);
    return $value;
  }
}

?>