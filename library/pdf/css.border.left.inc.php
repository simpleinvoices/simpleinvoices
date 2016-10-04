<?php
// $Header: /cvsroot/html2ps/css.border.left.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderLeft extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BORDER_LEFT;
  }

  function getPropertyName() {
    return 'border-left';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    $border = CSSBorder::parse($value);
    return $border->left;
  }
}

?>