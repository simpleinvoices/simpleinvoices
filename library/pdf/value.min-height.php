<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueMinHeight extends CSSValuePercentage {
  function fromString($value) {
    return CSSValuePercentage::_fromString($value, new ValueMinHeight);
  }

  function &copy() {
    $value =& parent::_copy(new ValueMinHeight);
    return $value;
  }
}

?>