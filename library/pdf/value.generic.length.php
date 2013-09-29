<?php

require_once(HTML2PS_DIR.'value.generic.php');

class Value extends CSSValue {
  /**
   * Type of unit this value is measured with
   */
  var $_unit;
  var $_number;
  var $_points;

  function scale($scale) {
    $this->_number *= $scale;
    $this->_points *= $scale;
  }

  function &copy() {
    $value =& new Value;
    $value->_unit   = $this->_unit;
    $value->_number = $this->_number;
    $value->_points = $this->_points;
    return $value;
  }

  function getPoints() {
    return $this->_points;
  }

  function Value() {
    $this->_unit   = UNIT_PT;
    $this->_number = 0;
    $this->_points = 0;
  }

  function &fromData($number, $unit) {
    $value =& new Value;
    $value->_unit   = $unit;
    $value->_number = $number;
    $value->_points = 0;
    return $value;    
  }

  /**
   * Create  new  object using  data  contained  in  string CSS  value
   * representation
   */
  function &fromString($string_value) {
    $value =& new Value;
    $value->_unit   = $value->unit_from_string($string_value);
    $value->_number = (double)$string_value;
    $value->_points = 0;
    return $value;
  }

  /**
   * @static
   */
  function unit_from_string($value) {
    $unit = substr($value, strlen($value)-2, 2);
    switch ($unit) {
    case 'pt':
      return UNIT_PT;
    case 'px':
      return UNIT_PX;
    case 'mm':
      return UNIT_MM;
    case 'cm':
      return UNIT_CM;
    case 'ex':
      return UNIT_EX;
    case 'em':
      return UNIT_EM;
    case 'in':
      return UNIT_IN;
    case 'pc':
      return UNIT_PC;
    default:
      return UNIT_NONE;
    }
  }

  function units2pt($font_size) {
    $this->_points = $this->toPt($font_size);
  }

  function toPt($font_size) {
    switch ($this->_unit) {
    case UNIT_PT:
      return pt2pt($this->_number);
    case UNIT_PX:
      return px2pt($this->_number);
    case UNIT_MM:
      return pt2pt(mm2pt($this->_number));
    case UNIT_CM:
      return pt2pt(mm2pt($this->_number*10));
    case UNIT_EM:
      return em2pt($this->_number, $font_size);
    case UNIT_EX:
      return ex2pt($this->_number, $font_size);
    case UNIT_IN:
      return pt2pt($this->_number * 72); // points used by CSS 2.1 are equal to 1/72nd of an inch.
    case UNIT_PC:
      return pt2pt($this->_number * 12); // 1 pica equals to 12 points.
    default:
      global $g_config;

      if ($g_config['mode'] === 'quirks') {
        return px2pt($this->_number);
      } else {
        return 0;
      };
    };
  }
}

?>