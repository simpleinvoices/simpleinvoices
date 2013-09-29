<?php

class CSSFontSize extends CSSSubFieldProperty {
  var $_defaultValue;

  function CSSFontSize(&$owner, $field) {
    $this->CSSSubFieldProperty($owner, $field);

    $this->_defaultValue = Value::fromData(BASE_FONT_SIZE_PT, UNIT_PT);
  }

  function default_value() {
    return $this->_defaultValue;
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    $value = trim(strtolower($value));

    switch(strtolower($value)) {
    case "xx-small":
      return Value::fromData(BASE_FONT_SIZE_PT*3/5, UNIT_PT);
    case "x-small":
      return Value::fromData(BASE_FONT_SIZE_PT*3/4, UNIT_PT);
    case "small":
      return Value::fromData(BASE_FONT_SIZE_PT*8/9, UNIT_PT);
    case "medium":
      return Value::fromData(BASE_FONT_SIZE_PT, UNIT_PT);
    case "large":
      return Value::fromData(BASE_FONT_SIZE_PT*6/5, UNIT_PT);
    case "x-large":
      return Value::fromData(BASE_FONT_SIZE_PT*3/2, UNIT_PT);
    case "xx-large":
      return Value::fromData(BASE_FONT_SIZE_PT*2/1, UNIT_PT);
    };
  
    switch(strtolower($value)) {
    case "larger":
      return Value::fromData(1.2, UNIT_EM);
    case "smaller":
      return Value::fromData(0.83, UNIT_EM); // 0.83 = 1/1.2
    };

    if (preg_match("/(\d+\.?\d*)%/i", $value, $matches)) {
      return Value::fromData($matches[1]/100, UNIT_EM);
    };

    return Value::fromString($value);
  }

  function getPropertyCode() {
    return CSS_FONT_SIZE;
  }

  function getPropertyName() {
    return 'font-size';
  }
}

?>