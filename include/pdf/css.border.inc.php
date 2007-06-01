<?php
// $Header: /cvsroot/html2ps/css.border.inc.php,v 1.21 2006/05/27 15:33:26 Konstantin Exp $

define('BS_NONE',   0);
define('BS_SOLID',  1);
define('BS_INSET',  2);
define('BS_GROOVE', 3);
define('BS_RIDGE',  4);
define('BS_OUTSET', 5);
define('BS_DASHED', 6);
define('BS_DOTTED', 7);
define('BS_DOUBLE', 8);

class EdgePDF {
  var $_width;
  var $color;
  var $style;

  function EdgePDF($edge) {
    $this->_width = units2pt($edge['width']);
    $this->color  = new Color($edge['color'], is_transparent($edge['color']));
    $this->style  = $edge['style'];
  }

  function is_visible() {
    return ($this->_width > 0) && ($this->style !== BS_NONE);
  }

  function get_width() {
    if ($this->style == BS_NONE) { 
      return 0; 
    }
    return $this->_width;
  }

  function show(&$viewport, &$box,
                $x1, $y1,
                $x2, $y2,
                $x3, $y3,
                $x4, $y4,
                $hilight) {

    // If this border have 'transparent' color value, we just will not draw it
    //
    if ($this->color->transparent) { return; };

    switch ($this->style) {
    case BS_SOLID:
      $this->color->apply($viewport);

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      break;

    case BS_INSET:
      if ($hilight) {
        $this->color->apply($viewport);
      } else {
        $color = $this->color;
        $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
        $color->apply($viewport);
      };

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      break;

    case BS_GROOVE:
      $color = $this->color;
      $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
      $color->apply($viewport);

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      $this->color->apply($viewport);
      $viewport->setlinewidth(px2pt(1));

      if ($hilight) {
        $viewport->moveto($x1, $y1);
        $viewport->lineto($x2, $y2);
        $viewport->stroke();
      } else {
        $viewport->moveto($x3, $y3);
        $viewport->lineto($x4, $y4);
        $viewport->stroke();
      };

      break;

    case BS_RIDGE:
      $this->color->apply($viewport);

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      $color = $this->color;

      $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
      $color->apply($viewport);
      $viewport->setlinewidth(px2pt(1));

      if ($hilight) {
        $viewport->moveto($x1, $y1);
        $viewport->lineto($x2, $y2);
        $viewport->stroke();
      } else {
        $viewport->moveto($x3, $y3);
        $viewport->lineto($x4, $y4);
        $viewport->stroke();
      };

      break;

    case BS_OUTSET:
      if (!$hilight) {
        $this->color->apply($viewport);
      } else {
        $color = $this->color;
        $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
        $color->apply($viewport);
      };

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      break;

    case BS_DASHED:
      $this->color->apply($viewport);

      $viewport->dash($this->_width*4, $this->_width*5);
      $viewport->setlinewidth($this->_width);
      $viewport->moveto(($x1+$x4)/2,($y1+$y4)/2);
      $viewport->lineto(($x2+$x3)/2,($y2+$y3)/2);
      $viewport->stroke();
      
      // Restore solid line
      $viewport->dash(1,0);
      break;

    case BS_DOTTED:
      $this->color->apply($viewport);

      $viewport->dash($this->_width, $this->_width*2);
      $viewport->setlinewidth($this->_width);
      $viewport->moveto(($x1+$x4)/2,($y1+$y4)/2);
      $viewport->lineto(($x2+$x3)/2,($y2+$y3)/2);
      $viewport->stroke();

      // Restore solid line
      $viewport->dash(1,0);
      break;

    case BS_DOUBLE:
      $this->color->apply($viewport);
      $viewport->setlinewidth(px2pt(1));

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->stroke();

      $viewport->moveto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->stroke();
      break;
    case BS_NONE:
    default:
      break;
    }
  }
}

class BorderPDF {
  function BorderPDF($border) {
    $this->left   = new EdgePDF($border['left']);
    $this->right  = new EdgePDF($border['right']);
    $this->top    = new EdgePDF($border['top']);
    $this->bottom = new EdgePDF($border['bottom']);
  }

  function is_default() {
    return 
      $this->left->style   == BS_NONE &&
      $this->right->style  == BS_NONE &&
      $this->top->style    == BS_NONE &&
      $this->bottom->style == BS_NONE;
  }

  function show(&$viewport, $box) {
    // Show left border
    if ($this->left->is_visible()) {
      $this->left->show($viewport, $box,
                        $box->get_left_border()  , $box->get_bottom_border(),
                        $box->get_left_border()  , $box->get_top_border(),
                        $box->get_left_border()+$this->left->get_width(), $box->get_top_border()-$this->top->get_width(),
                        $box->get_left_border()+$this->left->get_width(), $box->get_bottom_border()+$this->bottom->get_width(),
                        true);
    }

    // Show right border
    if ($this->right->is_visible()) {
      $this->right->show($viewport, $box,
                         $box->get_right_border()  , $box->get_bottom_border(),
                         $box->get_right_border()  , $box->get_top_border(),
                         $box->get_right_border()-$this->right->get_width(), $box->get_top_border()-$this->top->get_width(),
                         $box->get_right_border()-$this->right->get_width(), $box->get_bottom_border()+$this->bottom->get_width(),
                         false);
    }

    // Show top border
    if ($this->top->is_visible()) {
      $this->top->show($viewport, $box,
                       $box->get_left_border()  , $box->get_top_border(),
                       $box->get_right_border() , $box->get_top_border(),
                       $box->get_right_border()-$this->right->get_width() , $box->get_top_border() - $this->top->get_width(),
                       $box->get_left_border() +$this->left->get_width()  , $box->get_top_border() - $this->top->get_width(),
                       true);
    }

    // Show bottom border
    if ($this->bottom->is_visible()) {
      $this->bottom->show($viewport, $box,
                          $box->get_left_border()  , $box->get_bottom_border(),
                          $box->get_right_border() , $box->get_bottom_border(),
                          $box->get_right_border()- $this->right->get_width() , $box->get_bottom_border() + $this->bottom->get_width(),
                          $box->get_left_border() + $this->left->get_width()  , $box->get_bottom_border() + $this->bottom->get_width(),
                          false);
    }
  }
}

define('BORDER_VALUE_COLOR',1);
define('BORDER_VALUE_WIDTH',2);
define('BORDER_VALUE_STYLE',3);

function detect_border_value_type($value) {
  if (preg_match("/\b(transparent|black|silver|gray|white|maroon|red|purple|fuchsia|green|lime|olive|yellow|navy|blue|teal|aqua|rgb(.*?))\b/i",$value)) { return BORDER_VALUE_COLOR; };
  // We must detect hecadecimal values separately, as #-sign will not match the \b metacharacter at the beginning of previous regexp
  if (preg_match("/#([[:xdigit:]]{3}|[[:xdigit:]]{6})\b/i",$value)) { return BORDER_VALUE_COLOR; };
  
  // Note that unit name is in general not required, so that we can meet rule like "border: 0" in CSS!
  if (preg_match("/\b(thin|medium|thick|[+-]?\d+(.\d*)?(em|ex|px|in|cm|mm|pt|pc)?)\b/i",$value)) { return BORDER_VALUE_WIDTH; };
  if (preg_match("/\b(none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset)\b/",$value)) { return BORDER_VALUE_STYLE; };
  return;
}

function is_default_border($border) {
  return $border == default_border();
}

function default_border() {
  return array('top'    => array('width' => '2px', 'color' => array(0,0,0), 'style' => BS_NONE),
               'right'  => array('width' => '2px', 'color' => array(0,0,0), 'style' => BS_NONE),
               'bottom' => array('width' => '2px', 'color' => array(0,0,0), 'style' => BS_NONE),
               'left'   => array('width' => '2px', 'color' => array(0,0,0), 'style' => BS_NONE));
}

function parse_border_width($value) {
  switch (strtolower($value)) {
    case 'thin':
      return "1px";
    case 'medium':
      return "3px";
    case 'thick':
      return "5px";
    default:
      return $value;
  };
}

function push_border($size) {
  global $g_border;
  array_unshift($g_border, $size);
}

function pop_border() {
  global $g_border;
  array_shift($g_border);
}

function get_border() {
  global $g_border;
  return $g_border[0];
}

function css_border($value, $root)        { return css_border_x($value, $root, "css_border_color"       , "css_border_width", "css_border_style"); };
function css_border_top($value, $root)    { return css_border_x($value, $root, "css_border_top_color"   , "css_border_top_width", "css_border_top_style"); };
function css_border_right($value, $root) { return css_border_x($value, $root, "css_border_right_color" , "css_border_right_width" , "css_border_right_style"); };
function css_border_bottom($value, $root)   { return css_border_x($value, $root, "css_border_bottom_color", "css_border_bottom_width", "css_border_bottom_style"); };
function css_border_left($value, $root)  { return css_border_x($value, $root, "css_border_left_color"  , "css_border_left_width", "css_border_left_style"); };

function css_border_x($value, $root, $color_fun, $width_fun, $style_fun) {
  // Remove spaces between color values in rgb() color definition; this will allow us to tread 
  // this declaration as a single value
  $value = preg_replace("/\s*,\s*/",",",$value);

  // Remove spaces before and after parens in rgb color definition
  $value = preg_replace("/rgb\s*\(\s*(.*?)\s*\)/", 'rgb(\1)', $value);

  $subvalues = explode(" ", $value);

  foreach ($subvalues as $subvalue) {
    $subvalue = trim(strtolower($subvalue));

    switch (detect_border_value_type($subvalue)) {
      case BORDER_VALUE_COLOR:
        $color_fun($subvalue, $root);
        break;
      case BORDER_VALUE_WIDTH:
        $width_fun($subvalue, $root);
        break;
      case BORDER_VALUE_STYLE:
        $style_fun($subvalue, $root);
        break;
    };
  };
}

function css_border_x_style($value, $root, $mode) {
  $border = get_border();
  switch ($value) {
    case "solid":  $border[$mode]['style'] = BS_SOLID ; break;
    case "dashed": $border[$mode]['style'] = BS_DASHED; break;
    case "dotted": $border[$mode]['style'] = BS_DOTTED; break;
    case "double": $border[$mode]['style'] = BS_DOUBLE; break;
    case "inset":  $border[$mode]['style'] = BS_INSET; break;
    case "outset": $border[$mode]['style'] = BS_OUTSET; break;
    case "groove": $border[$mode]['style'] = BS_GROOVE; break;
    case "ridge":  $border[$mode]['style'] = BS_RIDGE; break;
    default:       $border[$mode]['style'] = BS_NONE  ; break;
  };
  pop_border(); push_border($border);
}

function css_border_top_style($value, $root)    { css_border_x_style($value, $root,'top'); }
function css_border_bottom_style($value, $root) { css_border_x_style($value, $root,'bottom'); }
function css_border_left_style($value, $root)   { css_border_x_style($value, $root,'left'); }
function css_border_right_style($value, $root)  { css_border_x_style($value, $root,'right'); }

function css_border_style($value, $root)  { 
  css_border_x_style($value, $root, 'top'); 
  css_border_x_style($value, $root, 'bottom');
  css_border_x_style($value, $root, 'left');
  css_border_x_style($value, $root, 'right'); 
}

function css_border_top_color($value, $root) {
  $border = get_border();
  $color = parse_color_declaration($value, array(0,0,0));
  $border['top']['color']    = $color;
  pop_border();
  push_border($border);
}

function css_border_right_color($value, $root) {
  $border = get_border();
  $color = parse_color_declaration($value, array(0,0,0));

  $border['right']['color']    = $color;
  pop_border();
  push_border($border);
}

function css_border_bottom_color($value, $root) {
  $border = get_border();
  $color = parse_color_declaration($value, array(0,0,0));
  $border['bottom']['color']    = $color;
  pop_border();
  push_border($border);
}

function css_border_left_color($value, $root) {
  $border = get_border();
  $color = parse_color_declaration($value, array(0,0,0));
  $border['left']['color']    = $color;
  pop_border();
  push_border($border);
}

function css_border_color($value, $root) {
  $border = get_border();

  $subvalues = explode(" ",$value);
  switch(count($subvalues)) {
    case 1:
      $c1 = parse_color_declaration($subvalues[0], array(0,0,0));

      $border['top']['color']    = $c1;
      $border['right']['color']  = $c1;
      $border['bottom']['color'] = $c1;
      $border['left']['color']   = $c1;
      break;
    case 2:
      $c1 = parse_color_declaration($subvalues[0], array(0,0,0));
      $c2 = parse_color_declaration($subvalues[1], array(0,0,0));
      $border['top']['color']    = $c1;
      $border['right']['color']  = $c2;
      $border['bottom']['color'] = $c1;
      $border['left']['color']   = $c2;
      break;
    case 3:
      $c1 = parse_color_declaration($subvalues[0], array(0,0,0));
      $c2 = parse_color_declaration($subvalues[1], array(0,0,0));
      $c3 = parse_color_declaration($subvalues[2], array(0,0,0));
      $border['top']['color']    = $c1;
      $border['right']['color']  = $c2;
      $border['bottom']['color'] = $c3;
      $border['left']['color']   = $c2;
      break;
    case 4:
      $c1 = parse_color_declaration($subvalues[0], array(0,0,0));
      $c2 = parse_color_declaration($subvalues[1], array(0,0,0));
      $c3 = parse_color_declaration($subvalues[2], array(0,0,0));
      $c4 = parse_color_declaration($subvalues[3], array(0,0,0));
      $border['top']['color']    = $c1;
      $border['right']['color']  = $c2;
      $border['bottom']['color'] = $c3;
      $border['left']['color']   = $c4;
      break;
  };

  pop_border();
  push_border($border);
}

function css_border_top_width($value, $root) {
  $border = get_border();
  $border['top']['width'] = parse_border_width($value);;
  pop_border();
  push_border($border);
}

function css_border_right_width($value, $root) {
  $border = get_border();
  $border['right']['width'] = parse_border_width($value);;
  pop_border();
  push_border($border);
}

function css_border_bottom_width($value, $root) {
  $border = get_border();
  $border['bottom']['width'] = parse_border_width($value);;
  pop_border();
  push_border($border);
}

function css_border_left_width($value, $root) {
  $border = get_border();
  $border['left']['width'] = parse_border_width($value);;
  pop_border();
  push_border($border);
}

function css_border_width($value, $root) {
  $border = get_border();

  $subvalues = explode(" ",$value);
  switch(count($subvalues)) {
    case 1:
      $c1 = parse_border_width($subvalues[0], array(0,0,0));
      $border['top']['width']    = $c1;
      $border['right']['width']  = $c1;
      $border['bottom']['width'] = $c1;
      $border['left']['width']   = $c1;
      break;
    case 2:
      $c1 = parse_border_width($subvalues[0], array(0,0,0));
      $c2 = parse_border_width($subvalues[1], array(0,0,0));
      $border['top']['width']    = $c1;
      $border['right']['width']  = $c2;
      $border['bottom']['width'] = $c1;
      $border['left']['width']   = $c2;
      break;
    case 3:
      $c1 = parse_border_width($subvalues[0], array(0,0,0));
      $c2 = parse_border_width($subvalues[1], array(0,0,0));
      $c3 = parse_border_width($subvalues[2], array(0,0,0));
      $border['top']['width']    = $c1;
      $border['right']['width']  = $c2;
      $border['bottom']['width'] = $c3;
      $border['left']['width']   = $c2;
      break;
    case 4:
      $c1 = parse_border_width($subvalues[0], array(0,0,0));
      $c2 = parse_border_width($subvalues[1], array(0,0,0));
      $c3 = parse_border_width($subvalues[2], array(0,0,0));
      $c4 = parse_border_width($subvalues[3], array(0,0,0));
      $border['top']['width']    = $c1;
      $border['right']['width']  = $c2;
      $border['bottom']['width'] = $c3;
      $border['left']['width']   = $c4;
      break;
  };
  
  pop_border();
  push_border($border);
}

global $g_border;
$g_border = array(default_border());

?>