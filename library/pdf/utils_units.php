<?php
// $Header: /cvsroot/html2ps/utils_units.php,v 1.22 2007/01/24 18:56:10 Konstantin Exp $

function round_units($value) {
  return round($value,2);
}

function pt2pt($pt) { 
  return $pt * $GLOBALS['g_pt_scale'];
}

function px2pt($px) {
  global $g_px_scale;
  return $px * $g_px_scale;
}

function mm2pt($mm) {
  return $mm * 2.834645669;
}

function units_mul($value, $koeff) {
  if (preg_match("/(pt|pc|px|mm|cm|em|ex)$/",$value)) {
    $units = substr($value, strlen($value)-2,2);
  } else {
    $units = "";
  };
  
  return sprintf("%.2f%s",
                 round((double)$value * $koeff,2),
                 $units);
}

function punits2pt($value, $font_size) {
  $value = trim($value);

  // Check if current value is percentage
  if (substr($value, strlen($value)-1, 1) === "%") {
    return array((float)$value, true);
  } else {
    return array(units2pt($value, $font_size), false);
  }
}

function em2pt($value, $font_size) {
  return $font_size * (double)$value * EM_KOEFF;
}

function ex2pt($value, $font_size) {
  return $font_size * (double)$value * EX_KOEFF;
}

function units2pt($value, $font_size = null) {
  $unit = Value::unit_from_string($value);

  switch ($unit) {
  case UNIT_PT:
    return pt2pt((double)$value);
  case UNIT_PX:
    return px2pt((double)$value);
  case UNIT_MM:
    return pt2pt(mm2pt((double)$value));
  case UNIT_CM:
    return pt2pt(mm2pt((double)$value*10));
  case UNIT_EM:
    return em2pt((double)$value, $font_size);
  case UNIT_EX:
    return ex2pt((double)$value, $font_size);
  case UNIT_IN:
    return pt2pt((double)$value*72); // points used by CSS 2.1 are equal to 1/72nd of an inch.
  case UNIT_PC:
    return pt2pt((double)$value*12); // 1 pica equals to 12 points.
  default:
    global $g_config;
    if ($g_config['mode'] === 'quirks') {
      return px2pt((double)$value);
    } else {
      return 0;
    };
  };
}

?>