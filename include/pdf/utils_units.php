<?php
// $Header: /cvsroot/html2ps/utils_units.php,v 1.13 2006/03/19 09:25:37 Konstantin Exp $

define('UNIT_PT', 0);
define('UNIT_PX', 1);
define('UNIT_MM', 2);
define('UNIT_CM', 3);
define('UNIT_EM', 4);
define('UNIT_EX', 5);

class Value {
  var $unit;
  var $number;

  function unit_from_string($value) {
    switch (substr($value, strlen($value)-2,2)) {
    case "pt":
      return UNIT_PT;
    case "px":
      return UNIT_PX;
    case "mm":
      return UNIT_MM;
    case "cm":
      return UNIT_CM;
    case "ex":
      return UNIT_EX;
    case "em":
      return UNIT_EM;
    }
  }
}

function pt2pt($pt) { 
  global $g_pt_scale;
  return $pt * $g_pt_scale;
}

function px2pt($px) {
  global $g_px_scale;
  return $px * $g_px_scale;
}

function mm2pt($mm) {
  return $mm*2.83464567;
}

function units_mul($value, $koeff) {
  if (preg_match("/(pt|px|mm|cm|em|ex)$/",$value)) {
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

function units2pt($value, $font_size = null) {
  $units = substr($value, strlen($value)-2,2);
  switch ($units) {
  case "pt":
    return pt2pt((double)$value);
  case "px":
    return px2pt((double)$value);
  case "mm":
    return mm2pt((double)$value);
  case "cm":
    return mm2pt((double)$value*10);
    // FIXME: check if it will work correcty in all situations (order of css rule application may vary).
  case "em":
    if (is_null($font_size)) {
      $fs = get_font_size();
      
//       $fs_parts = explode(" ", $fs);
//       if (count($fs_parts) == 2) {
//         return units2pt(((double)$value) * $fs_parts[0]*EM_KOEFF . $fs_parts[1]);
//       } else {
      return pt2pt(((double)$value) * $fs * EM_KOEFF);
//       };
    } else {
      return $font_size * (double)$value * EM_KOEFF;
    };
  case "ex":
    if (is_null($font_size)) {
      $fs = get_font_size();
//       $fs_parts = explode(" ", $fs);
//       if (count($fs_parts) == 2) {
//         return units2pt(((double)$value) * $fs_parts[0]*EX_KOEFF . $fs_parts[1]);
//       } else {
      return pt2pt(((double)$value) * $fs * EX_KOEFF);
//       };
    } else {
      return $font_size * (double)$value * EX_KOEFF;
    };
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