<?php
// $Header: /cvsroot/html2ps/css.border.inc.php,v 1.25 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'css.border.color.inc.php');
require_once(HTML2PS_DIR.'css.border.style.inc.php');
require_once(HTML2PS_DIR.'css.border.width.inc.php');

require_once(HTML2PS_DIR.'css.border.top.inc.php');
require_once(HTML2PS_DIR.'css.border.right.inc.php');
require_once(HTML2PS_DIR.'css.border.left.inc.php');
require_once(HTML2PS_DIR.'css.border.bottom.inc.php');

require_once(HTML2PS_DIR.'css.border.top.color.inc.php');
require_once(HTML2PS_DIR.'css.border.right.color.inc.php');
require_once(HTML2PS_DIR.'css.border.left.color.inc.php');
require_once(HTML2PS_DIR.'css.border.bottom.color.inc.php');

require_once(HTML2PS_DIR.'css.border.top.style.inc.php');
require_once(HTML2PS_DIR.'css.border.right.style.inc.php');
require_once(HTML2PS_DIR.'css.border.left.style.inc.php');
require_once(HTML2PS_DIR.'css.border.bottom.style.inc.php');

require_once(HTML2PS_DIR.'css.border.top.width.inc.php');
require_once(HTML2PS_DIR.'css.border.right.width.inc.php');
require_once(HTML2PS_DIR.'css.border.left.width.inc.php');
require_once(HTML2PS_DIR.'css.border.bottom.width.inc.php');

require_once(HTML2PS_DIR.'value.generic.length.php');
require_once(HTML2PS_DIR.'value.border.class.php');
require_once(HTML2PS_DIR.'value.border.edge.class.php');

define('BORDER_VALUE_COLOR',1);
define('BORDER_VALUE_WIDTH',2);
define('BORDER_VALUE_STYLE',3);

class CSSBorder extends CSSPropertyHandler {
  var $_defaultValue;

  function CSSBorder() {
    $this->CSSPropertyHandler(false, false);

    $this->_defaultValue = BorderPDF::create(array('top'    => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE),
                                                   'right'  => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE),
                                                   'bottom' => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE),
                                                   'left'   => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE)));
  }

  function default_value() {
    return $this->_defaultValue;
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    // Remove spaces between color values in rgb() color definition; this will allow us to tread 
    // this declaration as a single value
    $value = preg_replace("/\s*,\s*/",",",$value);

    // Remove spaces before and after parens in rgb color definition
    $value = preg_replace("/rgb\s*\(\s*(.*?)\s*\)/", 'rgb(\1)', $value);

    $subvalues = explode(" ", $value);

    $border = CSS::getDefaultValue(CSS_BORDER);

    foreach ($subvalues as $subvalue) {
      $subvalue = trim(strtolower($subvalue));

      switch (CSSBorder::detect_border_value_type($subvalue)) {
      case BORDER_VALUE_COLOR:
        $color_handler = CSS::get_handler(CSS_BORDER_COLOR);
        $border_color = $color_handler->parse($subvalue);
        $color_handler->setValue($border, $border_color);
        break;
      case BORDER_VALUE_WIDTH:
        $width_handler = CSS::get_handler(CSS_BORDER_WIDTH);
        $border_width = $width_handler->parse($subvalue);
        $width_handler->setValue($border, $border_width);
        break;
      case BORDER_VALUE_STYLE:
        $style_handler = CSS::get_handler(CSS_BORDER_STYLE);
        $border_style = $style_handler->parse($subvalue);
        $style_handler->setValue($border, $border_style);
        break;
      };
    };

    return $border;
  }

  function getPropertyCode() {
    return CSS_BORDER;
  }

  function getPropertyName() {
    return 'border';
  }

  function detect_border_value_type($value) {
    $color = _parse_color_declaration($value, $success);
    if ($success) { return BORDER_VALUE_COLOR; };

//     if (preg_match("/\b(transparent|black|silver|gray|white|maroon|red|purple|fuchsia|green|lime|olive|yellow|navy|blue|teal|aqua|rgb(.*?))\b/i",$value)) { return BORDER_VALUE_COLOR; };
//     // We must detect hecadecimal values separately, as #-sign will not match the \b metacharacter at the beginning of previous regexp
//     if (preg_match("/#([[:xdigit:]]{3}|[[:xdigit:]]{6})\b/i",$value)) { return BORDER_VALUE_COLOR; };
  
    // Note that unit name is in general not required, so that we can meet rule like "border: 0" in CSS!
    if (preg_match("/\b(thin|medium|thick|[+-]?\d+(.\d*)?(em|ex|px|in|cm|mm|pt|pc)?)\b/i",$value)) { return BORDER_VALUE_WIDTH; };
    if (preg_match("/\b(none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset)\b/",$value)) { return BORDER_VALUE_STYLE; };
    return;
  }
}

$border = new CSSBorder();
CSS::register_css_property($border);

CSS::register_css_property(new CSSBorderColor($border));
CSS::register_css_property(new CSSBorderWidth($border));
CSS::register_css_property(new CSSBorderStyle($border));

CSS::register_css_property(new CSSBorderTop($border, 'top'));
CSS::register_css_property(new CSSBorderRight($border, 'right'));
CSS::register_css_property(new CSSBorderBottom($border, 'bottom'));
CSS::register_css_property(new CSSBorderLeft($border, 'left'));

CSS::register_css_property(new CSSBorderLeftColor($border));
CSS::register_css_property(new CSSBorderTopColor($border));
CSS::register_css_property(new CSSBorderRightColor($border));
CSS::register_css_property(new CSSBorderBottomColor($border));

CSS::register_css_property(new CSSBorderLeftStyle($border));
CSS::register_css_property(new CSSBorderTopStyle($border));
CSS::register_css_property(new CSSBorderRightStyle($border));
CSS::register_css_property(new CSSBorderBottomStyle($border));

CSS::register_css_property(new CSSBorderLeftWidth($border));
CSS::register_css_property(new CSSBorderTopWidth($border));
CSS::register_css_property(new CSSBorderRightWidth($border));
CSS::register_css_property(new CSSBorderBottomWidth($border));

?>