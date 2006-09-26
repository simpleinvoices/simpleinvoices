<?php
// $Header: /cvsroot/html2ps/css.list-style.inc.php,v 1.4 2006/03/19 09:25:36 Konstantin Exp $

class ListStyleValue {
  var $image;
  var $position;
  var $type;

  function is_default() {
    return 
      $this->image->is_default() &&
      $this->position == CSSListStylePosition::default_value() &&
      $this->type     == CSSListStyleType::default_value();
  }

  function copy() {
    $object = new ListStyleValue;

    $object->image    = $this->image->copy();
    $object->position = $this->position;
    $object->type     = $this->type;
    return $object;
  }
}

class CSSListStyle extends CSSProperty {
  // CSS 2.1: list-style is inherited
  function CSSListStyle() { 
    $this->default_value = new ListStyleValue;
    $this->default_value->image    = CSSListStyleImage::default_value();
    $this->default_value->position = CSSListStylePosition::default_value();
    $this->default_value->type     = CSSListStyleType::default_value();

    $this->CSSProperty(true, true); 
  }

  function parse($value, &$pipeline) { 
    $style = new ListStyleValue;
    $style->image     = CSSListStyleImage::parse($value, $pipeline);
    $style->position  = CSSListStylePosition::parse($value);
    $style->type      = CSSListStyleType::parse($value);

    return $style;
  }

  function default_value() { return $this->default_value; }
}

$ls = new CSSListStyle;
register_css_property('list-style', $ls);
register_css_property('list-style-image',    new CSSListStyleImage($ls,    'image'));
register_css_property('list-style-position', new CSSListStylePosition($ls, 'position'));
register_css_property('list-style-type',     new CSSListStyleType($ls,     'type'));

?>