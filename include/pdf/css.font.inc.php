<?php
// $Header: /cvsroot/html2ps/css.font.inc.php,v 1.22 2006/05/27 15:33:27 Konstantin Exp $

require_once('font.resolver.class.php');
require_once('font.constants.inc.php');

function default_font_size() {
  return BASE_FONT_SIZE_PT."pt";
}

function get_font_family() {
  global $g_font_family;
  return $g_font_family[0];
}

function push_font_family($family) {  global $g_font_family;  array_unshift($g_font_family, $family);}
function pop_font_family() {  global $g_font_family;  array_shift($g_font_family);}

function push_font_style($weight) {  global $g_font_style;  array_unshift($g_font_style, $weight);}
function pop_font_style() {  global $g_font_style;  array_shift($g_font_style);}
function get_font_style() {  global $g_font_style;  return $g_font_style[0];}

function push_font_weight($weight) {  global $g_font_weight;  array_unshift($g_font_weight, $weight);}
function pop_font_weight() {  global $g_font_weight;  array_shift($g_font_weight);}
function get_font_weight() {  global $g_font_weight;  return $g_font_weight[0];}

function push_font_size($size) { global $g_font_size; array_unshift($g_font_size, $size);}
function pop_font_size() {  global $g_font_size;  array_shift($g_font_size); }
function get_font_size() {  global $g_font_size;  return trim($g_font_size[0]); }

function get_base_font_size() {
  global $g_font_size;

  for ($i=0; $i<count($g_font_size); $i++) {
    $unit = value::unit_from_string($g_font_size[$i]);
    if ($unit != UNIT_EX && $unit != UNIT_EM) {
      $font_size = units2pt($g_font_size[$i]);
      for ($j=$i-1; $j>=0; $j--) {
        $font_size = units2pt($g_font_size[$j], $font_size);
      };

      return $font_size;
    };
  }
  return 0;
}

function parse_font_family($value) {
  $subvalues = preg_split("/\s*,\s*/",$value);

  foreach ($subvalues as $subvalue) {
    $subvalue = trim(strtolower($subvalue));   
    
    // Check if current subvalue is not empty (say, in case of 'font-family:;' or 'font-family:family1,,family2;')
    if ($subvalue !== "") {

      // Some multi-word font family names can be enclosed in quotes; remove them
      if ($subvalue{0} == "'") {
        $subvalue = substr($subvalue,1,strlen($subvalue)-2);
      } elseif ($subvalue{0} == '"') {
        $subvalue = substr($subvalue,1,strlen($subvalue)-2);
      };
      
      global $g_font_resolver;
      if ($g_font_resolver->have_font_family($subvalue)) { return $subvalue; };

      global $g_font_resolver_pdf;
      if ($g_font_resolver_pdf->have_font_family($subvalue)) { return $subvalue; };
    };
  };
  // Unknown family type
  return "times";
}

function parse_font_style($value) {
  $value = trim(strtolower($value));
  switch ($value) {
    case "normal":
      return FS_NORMAL;
    case "italic":
      return FS_ITALIC;
    case "oblique":
      return FS_OBLIQUE;
  };
}

function parse_weight($value) {
  switch ($value) {
  case "bold":
  case "700":
  case "800":
  case "900":
    return WEIGHT_BOLD;
  default:
    return WEIGHT_NORMAL;
  };
}

function ps_set_font($encoding) {
  global $g_font_resolver;
  $data = get_font_size() . " " . $g_font_resolver->ps_font_family(get_font_family(),get_font_weight(),get_font_style(),$encoding);

  return $data;
}

define('FONT_VALUE_STYLE',0);
define('FONT_VALUE_WEIGHT',1);
define('FONT_VALUE_SIZE',2);
define('FONT_VALUE_FAMILY',3);

function detect_font_value_type($value) {
  if (preg_match("/^normal|italic|oblique$/",$value)) { return FONT_VALUE_STYLE; }
  if (preg_match("/^normal|bold|bolder|lighter|[1-9]00$/",$value)) { return FONT_VALUE_WEIGHT; }

  if (preg_match("/^\d+\.?\d*%$/",$value)) { return FONT_VALUE_SIZE; }
  if (preg_match("/^xx-small|x-small|small|medium|large|x-large|xx-large$/",$value)) { return FONT_VALUE_SIZE; }
  if (preg_match("/^larger|smaller$/",$value)) { return FONT_VALUE_SIZE; }
  if (preg_match("/^\d*(.\d*)?(pt|pc|in|mm|cm|px|em|ex)$/",$value)) { return FONT_VALUE_SIZE; }

  return FONT_VALUE_FAMILY;
}

// CSS 'font' property handling/parsing function 
function css_font($value, $root) {
  // according to CSS 2.1 standard,
  // value of 'font' CSS property can be represented as follows:
  //   [ <'font-style'> || <'font-variant'> || <'font-weight'> ]? <'font-size'> [ / <'line-height'> ]? <'font-family'> ] | 
  //   caption | icon | menu | message-box | small-caption | status-bar | inherit

  // Note that font-family value, unlike other values, can contain spaces (in this case it should be quoted)
  // Breaking value by spaces, we'll break such multi-word families.

  // Replace all white space sequences with only one space; 
  // Remove spaces after commas; it will allow us 
  // to split value correctly using look-backward expressions
  $value = preg_replace("/\s+/"," ",$value);
  $value = preg_replace("/,\s+/",",",$value);

  // Split value to subvalues by all whitespaces NOT preceeded by comma;
  // thus, we'll keep all alternative font-families together instead of breaking them.
  // Still we have a problem with multi-word family names.
  $subvalues = preg_split("/ /",$value);

  // Let's scan subvalues we've received and join values containing multiword family names
  $family_start = 0;
  $family_running = false;
  $family_double_quote = false;;
  for ($i=0; $i < count($subvalues); $i++) {
    $current_value = $subvalues[$i];

    if ($family_running) {
      $subvalues[$family_start] .= " " . $subvalues[$i];
      
      // Remove this subvalues from the subvalue list at all
      array_splice($subvalues, $i, 1);
      $i--;
    }

    // Check if current subvalue contains beginning of multi-word family name 
    // We can detect it by searching for single or double quote without pair
    if ($family_running && $family_double_quote && !preg_match('/^[^"]*("[^"]*")*[^"]*$/',$current_value)) {
      $family_running = false;
    } elseif ($family_running && !$family_double_quote && !preg_match("/^[^']*('[^']*')*[^']*$/",$current_value)) {
      $family_running = false;
    } elseif (!$family_running && !preg_match("/^[^']*('[^']*')*[^']*$/",$current_value)) {
      $family_running = true;
      $family_start = $i;
      $family_double_quote = false;
    } elseif (!$family_running && !preg_match('/^[^"]*("[^"]*")*[^"]*$/',$current_value)) {
      $family_running = true;
      $family_start = $i;
      $family_double_quote = true;
    }
  };

  // Now process subvalues one-by-one. 
  foreach ($subvalues as $subvalue) {
    $subvalue = trim(strtolower($subvalue));

    switch (detect_font_value_type($subvalue)) {
      case FONT_VALUE_STYLE:
        css_font_style($subvalue, $root);
        break;
      case FONT_VALUE_WEIGHT:
        css_font_weight($subvalue, $root);
        break;
      case FONT_VALUE_SIZE:
        css_font_size($subvalue, $root);
        break;
      case FONT_VALUE_FAMILY:
        css_font_family($subvalue, $root);
        break;
    };
  };
}

function css_font_family($value, $root) {
  pop_font_family();

  push_font_family(parse_font_family($value));
}

function css_font_size($value, $root) {
  $value = trim(strtolower($value));

  pop_font_size();

  switch(strtolower($value)) {
    case "xx-small":
      push_font_size((BASE_FONT_SIZE_PT*3/5)."pt");
      return;
    case "x-small":
      push_font_size((BASE_FONT_SIZE_PT*3/4)."pt");
      return;
    case "small":
      push_font_size((BASE_FONT_SIZE_PT*8/9)."pt");
      return;
    case "medium":
      push_font_size((BASE_FONT_SIZE_PT)."pt");
      return;
    case "large":
      push_font_size((BASE_FONT_SIZE_PT*6/5)."pt");
      return;
    case "x-large":
      push_font_size((BASE_FONT_SIZE_PT*3/2)."pt");
      return;
    case "xx-large":
      push_font_size((BASE_FONT_SIZE_PT*2/1)."pt");
      return;
  };
  
  switch(strtolower($value)) {
    case "larger":
      push_font_size("1.2em");
      return;
    case "smaller":
      push_font_size("0.83em"); // 0.83 = 1/1.2
      return;
  };

  if (preg_match("/(\d+\.?\d*)%/i", $value, $matches)) {
    push_font_size($matches[1]/100 . "em");

    return;
  };

  push_font_size($value);
}

function css_font_style($value, $root) {
  pop_font_style();
  push_font_style(parse_font_style($value));
}

function css_font_weight($value, $root) {
  pop_font_weight();
  push_font_weight(parse_weight($value));
}

global $g_font_size, $g_font_weight, $g_font_style, $g_font_family;
$g_font_size        = array(default_font_size());
$g_font_weight      = array(WEIGHT_NORMAL);
$g_font_style       = array(FS_NORMAL);
$g_font_family      = array("times");

?>