<?php
// $Header: /cvsroot/html2ps/css.background.inc.php,v 1.23 2007/03/15 18:37:30 Konstantin Exp $

require_once(HTML2PS_DIR.'value.background.php');

class CSSBackground extends CSSPropertyHandler {
  var $default_value;

  function getPropertyCode() {
    return CSS_BACKGROUND;
  }

  function getPropertyName() {
    return 'background';
  }

  function __construct() {
    $this->default_value = new Background(CSSBackgroundColor::default_value(),
                                          CSSBackgroundImage::default_value(),
                                          CSSBackgroundRepeat::default_value(),
                                          CSSBackgroundPosition::default_value(),
                                          CSSBackgroundAttachment::default_value());

      parent::__construct(true, false);
  }

  function inherit($state, &$new_state) { 
    // Determine parent 'display' value
    $parent_display = $state[CSS_DISPLAY];

    // If parent is a table row, inherit the background settings
    $this->replace_array(($parent_display == 'table-row') ? $state[CSS_BACKGROUND] : $this->default_value(),
                         $new_state);
  }

  function default_value() {
    return $this->default_value->copy();
  }

  function parse($value, &$pipeline) {
    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    $background = new Background(CSSBackgroundColor::parse($value),
                                 CSSBackgroundImage::parse($value, $pipeline),
                                 CSSBackgroundRepeat::parse($value),
                                 CSSBackgroundPosition::parse($value),
                                 CSSBackgroundAttachment::parse($value));

    return $background;
  }
}

$bg = new CSSBackground;

CSS::register_css_property($bg);
$css_background_inc_reg1 = new CSSBackgroundColor($bg, '_color');
CSS::register_css_property($css_background_inc_reg1);
$css_background_inc_reg2 = new CSSBackgroundImage($bg, '_image');
CSS::register_css_property($css_background_inc_reg2);
$css_background_inc_reg3 = new CSSBackgroundRepeat($bg, '_repeat');
CSS::register_css_property($css_background_inc_reg3);
$css_background_inc_reg4 = new CSSBackgroundPosition($bg, '_position');
CSS::register_css_property($css_background_inc_reg4);
$css_background_inc_reg5 = new CSSBackgroundAttachment($bg, '_attachment');
CSS::register_css_property($css_background_inc_reg5);
