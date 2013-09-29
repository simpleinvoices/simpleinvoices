<?php
// The background-position value is an array containing two array for X and Y position correspondingly
// each coordinate-position array, in its turn containes two values:
// first, the numeric value of percentage or units
// second, flag indication that this value is a percentage (true) or plain unit value (false)

define('LENGTH_REGEXP',"(?:-?\d*\.?\d+(?:em|ex|px|in|cm|mm|pt|pc)\b|-?\d+(?:em|ex|px|in|cm|mm|pt|pc)\b)");
define('PERCENTAGE_REGEXP',"\b\d+%");
define('TEXT_REGEXP',"\b(?:top|bottom|left|right|center)\b");

define('BG_POSITION_SUBVALUE_TYPE_HORZ',1);
define('BG_POSITION_SUBVALUE_TYPE_VERT',2);

class CSSBackgroundPosition extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BACKGROUND_POSITION;
  }

  function getPropertyName() {
    return 'background-position';
  }

  function default_value() {
    return new BackgroundPosition(0,true,
                                  0,true);
  }

  function build_subvalue($value) {
    if ($value === "left" ||
        $value === "top") {
      return array(0, true);
    };

    if ($value === "right" ||
        $value === "bottom") {
      return array(100, true);
    };

    if ($value === "center") {
      return array(50, true);
    };

    if (substr($value, strlen($value)-1,1) === "%") {
      return array((int)$value, true);
    } else {
      return array($value, false);
    };
  }

  function build_value($x, $y) {
    return array(CSSBackgroundPosition::build_subvalue($x),
                 CSSBackgroundPosition::build_subvalue($y));
  }

  function detect_type($value) {
    if ($value === "left" || $value === "right") { return BG_POSITION_SUBVALUE_TYPE_HORZ; };
    if ($value === "top" || $value === "bottom") { return BG_POSITION_SUBVALUE_TYPE_VERT; };
    return null;
  }

  // See CSS 2.1 'background-position' for description of possible values
  //
  function parse_in($value) {
    if (preg_match("/(".LENGTH_REGEXP."|".PERCENTAGE_REGEXP."|".TEXT_REGEXP."|\b0\b)\s+(".LENGTH_REGEXP."|".PERCENTAGE_REGEXP."|".TEXT_REGEXP."|\b0\b)/", $value, $matches)) {
      $x = $matches[1];
      $y = $matches[2];

      $type_x = CSSBackgroundPosition::detect_type($x);
      $type_y = CSSBackgroundPosition::detect_type($y);

      if (is_null($type_x) && is_null($type_y)) {
        return CSSBackgroundPosition::build_value($x,$y);
      };

      if ($type_x == BG_POSITION_SUBVALUE_TYPE_HORZ || 
          $type_y == BG_POSITION_SUBVALUE_TYPE_VERT) {
        return CSSBackgroundPosition::build_value($x,$y);
      };

      return CSSBackgroundPosition::build_value($y,$x);
    };
    
    // These values should be processed separately at lastt
    if (preg_match("/\b(top)\b/",$value))    { return array(array(50, true),  array(0, true)); };
    if (preg_match("/\b(center)\b/",$value)) { return array(array(50, true),  array(50, true)); };
    if (preg_match("/\b(bottom)\b/",$value)) { return array(array(50, true),  array(100, true)); };
    if (preg_match("/\b(left)\b/",$value))   { return array(array(0, true),   array(50, true)); };
    if (preg_match("/\b(right)\b/",$value))  { return array(array(100, true), array(50, true)); };

    if (preg_match("/".LENGTH_REGEXP."|".PERCENTAGE_REGEXP."/", $value, $matches)) {
      $x = $matches[0];
      return CSSBackgroundPosition::build_value($x,"50%");
    };

    return null;
  }

  function parse($value) {
    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    $value = CSSBackgroundPosition::parse_in($value);
    return new BackgroundPosition($value[0][0], $value[0][1],
                                  $value[1][0], $value[1][1]);
  }
}
?>