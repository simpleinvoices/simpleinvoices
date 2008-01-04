<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueHeight extends CSSValuePercentage {
  function fromString($value) {
    return CSSValuePercentage::_fromString($value, new ValueHeight);
  }

  function &copy() {
    $value =& parent::_copy(new ValueHeight);
    return $value;
  }
}

?>