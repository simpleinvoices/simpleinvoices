<?php

class EdgePDF {
  var $width;
  var $color;
  var $style;

  var $_isDefaultColor;

  /**
   * Optimization: width/color fields of this class
   * never modified partially, so we could use one shared object 
   * as a default value
   */
  function EdgePDF() {
    static $default_width = null;
    if (is_null($default_width)) {
      $default_width =& Value::fromData(0, UNIT_PT);
    };

    static $default_color = null;
    if (is_null($default_color)) {
      $default_color =& new Color(array(0,0,0), true);
    };

    $this->width =& $default_width;
    $this->color =& $default_color;
    $this->style = BS_NONE;

    $this->_isDefaultColor = true;
  }

  function isDefaultColor() {
    return $this->_isDefaultColor;
  }

  function setColor(&$color) {
    if ($color != CSS_PROPERTY_INHERIT) {
      $this->color = $color->copy();
    } else {
      $this->color = CSS_PROPERTY_INHERIT;
    };

    $this->_isDefaultColor = false;
  }

  function doInherit(&$state, $code_width, $code_color, $code_style) {
    if ($this->width === CSS_PROPERTY_INHERIT) {
      $value = $state->getInheritedProperty($code_width);
      $this->width = $value->copy();
    };

    if ($this->color === CSS_PROPERTY_INHERIT) {
      $value = $state->getInheritedProperty($code_color);
      $this->width = $value->copy();
    };

    if ($this->style === CSS_PROPERTY_INHERIT) {
      $value = $state->getInheritedProperty($code_style);
      $this->width = $value;
    };
  }

  function &create($data) {
    $edge =& new EdgePDF();
    $edge->width = $data['width'];
    $edge->color =& new Color($data['color'], is_transparent($data['color']));
    $edge->style = $data['style'];
    $edge->_isDefaultColor = true;
    return $edge;
  }

  function &copy() {
    $edge =& new EdgePDF();

    if ($this->width != CSS_PROPERTY_INHERIT) {
      $edge->width = $this->width->copy();
    } else {
      $edge->width = CSS_PROPERTY_INHERIT;
    };
    
    if ($this->color != CSS_PROPERTY_INHERIT) {
      $edge->color = $this->color->copy();
    } else {
      $edge->color = CSS_PROPERTY_INHERIT;
    };

    $edge->style = $this->style;
    $edge->_isDefaultColor = $this->_isDefaultColor;

    return $edge;
  }

  function &get_color() {
    return $this->color;
  }

  function &get_style() {
    return $this->style;
  }

  function get_width() {
    if ($this->style === BS_NONE) { 
      return 0; 
    };

    return $this->width->getPoints();
  }

  function units2pt($base_font_size) {
    $this->width->units2pt($base_font_size);
  }

  function is_visible() {
    return 
      ($this->width->getPoints() > 0) && 
      ($this->style !== BS_NONE);
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
        $color = $this->color->copy();
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
      /**
       * Draw outer part
       */
      if ($hilight) {
        $this->color->apply($viewport);
      } else {
        $color = $this->color->copy();
        $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
        $color->apply($viewport);
      };

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      /**
       * Draw inner part
       */
      if ($hilight) {
        $color = $this->color->copy();
        $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
        $color->apply($viewport);
      } else {
        $this->color->apply($viewport);
      };

      $x1a = ($x1 + $x4) / 2;
      $y1a = ($y1 + $y4) / 2;

      $x2a = ($x2 + $x3) / 2;
      $y2a = ($y2 + $y3) / 2;

      $viewport->moveto($x1a, $y1a);
      $viewport->lineto($x2a, $y2a);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      break;

    case BS_RIDGE:
      /**
       * Draw outer part
       */
      if ($hilight) {
        $color = $this->color->copy();
        $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
        $color->apply($viewport);
      } else {
        $this->color->apply($viewport);
      };

      $viewport->moveto($x1, $y1);
      $viewport->lineto($x2, $y2);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();

      /**
       * Draw inner part
       */
      if ($hilight) {
        $this->color->apply($viewport);
      } else {
        $color = $this->color->copy();
        $color->blend(new Color(array(255,255,255), false), HILIGHT_COLOR_ALPHA);
        $color->apply($viewport);
      };

      $x1a = ($x1 + $x4) / 2;
      $y1a = ($y1 + $y4) / 2;

      $x2a = ($x2 + $x3) / 2;
      $y2a = ($y2 + $y3) / 2;

      $viewport->moveto($x1a, $y1a);
      $viewport->lineto($x2a, $y2a);
      $viewport->lineto($x3, $y3);
      $viewport->lineto($x4, $y4);
      $viewport->closepath();
      $viewport->fill();
      break;

    case BS_OUTSET:
      if (!$hilight) {
        $this->color->apply($viewport);
      } else {
        $color = $this->color->copy();
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

      $viewport->dash($this->width->getPoints()*4, $this->width->getPoints()*5);
      $viewport->setlinewidth($this->width->getPoints());
      $viewport->moveto(($x1+$x4)/2,($y1+$y4)/2);
      $viewport->lineto(($x2+$x3)/2,($y2+$y3)/2);
      $viewport->stroke();
      
      // Restore solid line
      $viewport->dash(1,0);
      break;

    case BS_DOTTED:
      $this->color->apply($viewport);

      $viewport->dash($this->width->getPoints(), $this->width->getPoints()*2);
      $viewport->setlinewidth($this->width->getPoints());
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

?>