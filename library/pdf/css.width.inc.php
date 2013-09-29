<?php
// $Header: /cvsroot/html2ps/css.width.inc.php,v 1.19 2007/01/24 18:55:53 Konstantin Exp $

require_once(HTML2PS_DIR.'css.min-width.inc.php');
require_once(HTML2PS_DIR.'css.property.sub.class.php');

class CSSCompositeWidth extends CSSPropertyHandler {
  function CSSCompositeWidth() {
    $this->CSSPropertyHandler(false, false); 
  }

  function getPropertyCode() {
    return CSS_HTML2PS_COMPOSITE_WIDTH;
  }

  function getPropertyName() {
    return '-html2ps-composite-width';
  }

  function default_value() {
    return new WCNone();
  }
}

class CSSWidth extends CSSSubProperty {
  function CSSWidth($owner) { 
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $min = $owner_value->_min_width;
    $owner_value = $value->copy();
    $owner_value->_min_width = $min;
  }

  function &getValue(&$owner_value) {
    return $owner_value;
  }

  function default_value() { 
    return new WCNone; 
  }

  function parse($value) { 
    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    // Check if user specified empty value
    if ($value === "") { return new WCNone; };

    // Check if this value is 'auto' - default value of this property
    if ($value === 'auto') {
      return new WCNone;
    };

    if (substr($value,strlen($value)-1,1) == "%") {
      // Percentage 
      return new WCFraction(((float)$value)/100);
    } else {
      // Constant
      return new WCConstant(trim($value));
    }
  }

  function getPropertyCode() {
    return CSS_WIDTH;
  }

  function getPropertyName() {
    return 'width';
  }
}

$width = new CSSCompositeWidth;
CSS::register_css_property($width);
CSS::register_css_property(new CSSWidth($width));
CSS::register_css_property(new CSSMinWidth($width, '_min_width'));

?>