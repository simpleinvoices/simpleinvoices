<?php
// $Header: /cvsroot/html2ps/css.pseudo.cellpadding.inc.php,v 1.6 2006/09/07 18:38:14 Konstantin Exp $

class CSSCellPadding extends CSSPropertyHandler {
  function CSSCellPadding() { 
    $this->CSSPropertyHandler(true, false); 
  }

  function default_value() { 
    return Value::fromData(1, UNIT_PX);
  }

  function parse($value) { 
    return Value::fromString($value);
  }

  function getPropertyCode() {
    return CSS_HTML2PS_CELLPADDING;
  }

  function getPropertyName() {
    return '-html2ps-cellpadding';
  }
}

CSS::register_css_property(new CSSCellPadding);

?>