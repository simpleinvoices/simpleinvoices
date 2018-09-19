<?php
// $Header: /cvsroot/html2ps/css.list-style.inc.php,v 1.8 2007/02/04 17:08:19 Konstantin Exp $

require_once(HTML2PS_DIR.'value.list-style.class.php');

class CSSListStyle extends CSSPropertyHandler {
  // CSS 2.1: list-style is inherited
  function __construct() {
    $this->default_value = new ListStyleValue;
    $this->default_value->image    = CSSListStyleImage::default_value();
    $this->default_value->position = CSSListStylePosition::default_value();
    $this->default_value->type     = CSSListStyleType::default_value();

    parent::__construct(true, true);
  }

  function parse($value, &$pipeline) { 
    $style = new ListStyleValue;
    $style->image     = CSSListStyleImage::parse($value, $pipeline);
    $style->position  = CSSListStylePosition::parse($value);
    $style->type      = CSSListStyleType::parse($value);

    return $style;
  }

  function default_value() { return $this->default_value; }

  function getPropertyCode() {
    return CSS_LIST_STYLE;
  }

  function getPropertyName() {
    return 'list-style';
  }
}

$ls = new CSSListStyle;
CSS::register_css_property($ls);
$css_list_style_inc_reg1 = new CSSListStyleImage($ls,    'image');
CSS::register_css_property($css_list_style_inc_reg1);
$css_list_style_inc_reg2 = new CSSListStylePosition($ls, 'position');
CSS::register_css_property($css_list_style_inc_reg2);
$css_list_style_inc_reg3 = new CSSListStyleType($ls,     'type');
CSS::register_css_property($css_list_style_inc_reg3);
