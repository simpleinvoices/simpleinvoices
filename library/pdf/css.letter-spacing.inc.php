<?php
// $Header: /cvsroot/html2ps/css.letter-spacing.inc.php,v 1.3 2006/09/07 18:38:14 Konstantin Exp $

class CSSLetterSpacing extends CSSPropertyHandler {
  var $_default_value;

  function CSSLetterSpacing() { 
    $this->CSSPropertyHandler(false, true); 

    $this->_default_value = Value::fromString("0");
  }

  function default_value() { 
    return $this->_default_value;
  }

  function parse($value) {
    $value = trim($value);

    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    if ($value === 'normal') { 
      return $this->_default_value; 
    };

    return Value::fromString($value);
  }

  function getPropertyCode() {
    return CSS_LETTER_SPACING;
  }

  function getPropertyName() {
    return 'letter-spacing';
  }
}

CSS::register_css_property(new CSSLetterSpacing);

?>
