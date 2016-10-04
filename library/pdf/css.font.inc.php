<?php
// $Header: /cvsroot/html2ps/css.font.inc.php,v 1.28 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'value.font.class.php');
require_once(HTML2PS_DIR.'font.resolver.class.php');
require_once(HTML2PS_DIR.'font.constants.inc.php');

require_once(HTML2PS_DIR.'css.font-family.inc.php');
require_once(HTML2PS_DIR.'css.font-style.inc.php');
require_once(HTML2PS_DIR.'css.font-weight.inc.php');
require_once(HTML2PS_DIR.'css.font-size.inc.php');
require_once(HTML2PS_DIR.'css.line-height.inc.php');

require_once(HTML2PS_DIR.'value.font.class.php');

define('FONT_VALUE_STYLE',0);
define('FONT_VALUE_WEIGHT',1);
define('FONT_VALUE_SIZE',2);
define('FONT_VALUE_FAMILY',3);

function detect_font_value_type($value) {
  if (preg_match("/^normal|italic|oblique$/",$value)) { return FONT_VALUE_STYLE; }
  if (preg_match("/^normal|bold|bolder|lighter|[1-9]00$/",$value)) { return FONT_VALUE_WEIGHT; }

  if (preg_match("#/#",$value)) { return FONT_VALUE_SIZE; }
  if (preg_match("#^\d+\.?\d*%$#",$value)) { return FONT_VALUE_SIZE; }
  if (preg_match("#^(xx-small|x-small|small|medium|large|x-large|xx-large)$#",$value)) { return FONT_VALUE_SIZE; }
  if (preg_match("#^(larger|smaller)$#",$value)) { return FONT_VALUE_SIZE; }
  if (preg_match("#^(\d*(.\d*)?(pt|pc|in|mm|cm|px|em|ex))$#",$value)) { return FONT_VALUE_SIZE; }

  return FONT_VALUE_FAMILY;
}

// ----

class CSSFont extends CSSPropertyHandler {
  var $_defaultValue;

  function CSSFont() {
    $this->CSSPropertyHandler(true, true);

    $this->_defaultValue = null;
  }

  function default_value() {
    if (is_null($this->_defaultValue)) {
      $this->_defaultValue = new ValueFont;

      $size_handler = CSS::get_handler(CSS_FONT_SIZE);
      $default_size = $size_handler->default_value();
      
      $this->_defaultValue->size   = $default_size->copy();
      $this->_defaultValue->weight = CSSFontWeight::default_value();
      $this->_defaultValue->style  = CSSFontStyle::default_value();
      $this->_defaultValue->family = CSSFontFamily::default_value();
      $this->_defaultValue->line_height = CSS::getDefaultValue(CSS_LINE_HEIGHT);
    };

    return $this->_defaultValue;
  }
  
  function parse($value) {   
    $font = CSS::getDefaultValue(CSS_FONT);

    if ($value === 'inherit') {
      $font->style       = CSS_PROPERTY_INHERIT;
      $font->weight      = CSS_PROPERTY_INHERIT;
      $font->size        = CSS_PROPERTY_INHERIT;
      $font->family      = CSS_PROPERTY_INHERIT;
      $font->line_height = CSS_PROPERTY_INHERIT;

      return $font;
    };


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
    $value = preg_replace("#\s*/\s*#","/",$value);

    // Split value to subvalues by all whitespaces NOT preceeded by comma;
    // thus, we'll keep all alternative font-families together instead of breaking them.
    // Still we have a problem with multi-word family names.
    $subvalues = preg_split("/ /",$value);

    // Let's scan subvalues we've received and join values containing multiword family names
    $family_start = 0;
    $family_running = false;
    $family_double_quote = false;;

    for ($i=0, $num_subvalues = count($subvalues); $i < $num_subvalues; $i++) {
      $current_value = $subvalues[$i];

      if ($family_running) {
        $subvalues[$family_start] .= " " . $subvalues[$i];
      
        // Remove this subvalues from the subvalue list at all
        array_splice($subvalues, $i, 1);

        $num_subvalues--;
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
      $subvalue_type = detect_font_value_type($subvalue);

      switch ($subvalue_type) {
      case FONT_VALUE_STYLE:
        $font->style = CSSFontStyle::parse($subvalue);
        break;
      case FONT_VALUE_WEIGHT:
        $font->weight = CSSFontWeight::parse($subvalue);
        break;
      case FONT_VALUE_SIZE:
        $size_subvalues = explode('/', $subvalue);
        $font->size = CSSFontSize::parse($size_subvalues[0]);
        if (isset($size_subvalues[1])) {
          $handler =& CSS::get_handler(CSS_LINE_HEIGHT);
          $font->line_height = $handler->parse($size_subvalues[1]);
        };
        break;
      case FONT_VALUE_FAMILY:
        $font->family = CSSFontFamily::parse($subvalue);
        break;
      };
    };

    return $font;
  }

  function getPropertyCode() {
    return CSS_FONT;
  }

  function getPropertyName() {
    return 'font';
  }

  function clearDefaultFlags(&$state) {
    parent::clearDefaultFlags($state);
    $state->setPropertyDefaultFlag(CSS_FONT_SIZE, false);
    $state->setPropertyDefaultFlag(CSS_FONT_STYLE, false);
    $state->setPropertyDefaultFlag(CSS_FONT_WEIGHT, false);
    $state->setPropertyDefaultFlag(CSS_FONT_FAMILY, false);
    $state->setPropertyDefaultFlag(CSS_LINE_HEIGHT, false);
  }
}

$font = new CSSFont;
CSS::register_css_property($font);
CSS::register_css_property(new CSSFontSize($font,   'size'));
CSS::register_css_property(new CSSFontStyle($font,  'style'));
CSS::register_css_property(new CSSFontWeight($font, 'weight'));
CSS::register_css_property(new CSSFontFamily($font, 'family'));
CSS::register_css_property(new CSSLineHeight($font, 'line_height'));

?>