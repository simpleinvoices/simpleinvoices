<?php
// $Header: /cvsroot/html2ps/css.background.inc.php,v 1.19 2006/03/19 09:25:35 Konstantin Exp $

class CSSBackground extends CSSProperty {
  var $default_value;

  function CSSBackground() {
    $this->default_value = new Background(CSSBackgroundColor::default_value(),
                                          CSSBackgroundImage::default_value(),
                                          CSSBackgroundRepeat::default_value(),
                                          CSSBackgroundPosition::default_value());

    $this->CSSProperty(false, false);
  }

  function inherit() { 
    // Determine parent 'display' value
    $handler =& get_css_handler('display');
    // note that as css handlers are evaluated in alphabetic order, parent display value still will be on the top of the stack
    $parent_display = $handler->get();

    // If parent is a table row, inherit the background settings
    $this->push(($parent_display == 'table-row') ? $this->get() : $this->default_value());
  }

  function default_value() {
    return $this->default_value->copy();
  }

  function parse($value, &$pipeline) {
    $background = new Background(CSSBackgroundColor::parse($value),
                                 CSSBackgroundImage::parse($value, $pipeline),
                                 CSSBackgroundRepeat::parse($value),
                                 CSSBackgroundPosition::parse($value));

    return $background;
  }
}

$bg = new CSSBackground;

register_css_property('background', $bg);
register_css_property('background-color'      ,new CSSBackgroundColor($bg, '_color'));
register_css_property('background-image'      ,new CSSBackgroundImage($bg, '_image'));
register_css_property('background-repeat'     ,new CSSBackgroundRepeat($bg, '_repeat'));
register_css_property('background-position'   ,new CSSBackgroundPosition($bg, '_position'));

?>