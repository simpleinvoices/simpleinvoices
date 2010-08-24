<?php

require_once(HTML2PS_DIR.'value.generic.percentage.php');

class ValueLeft extends CSSValuePercentage {
  function fromString($value) {
    return CSSValuePercentage::_fromString($value, new ValueLeft);
  }

  function &copy() {
    $value =& parent::_copy(new ValueLeft);
    return $value;
  }
}

?>