<?php
// $Header: /cvsroot/html2ps/css.right.inc.php,v 1.3 2006/03/19 09:25:36 Konstantin Exp $

class CSSRight extends CSSProperty {
  function CSSRight() { $this->CSSProperty(false, false); }

  function default_value() { return null; }

  function parse($value) {
    return $value;
  }

  function pdf() {
    return $this->get() === null ? null : units2pt($this->get());
  }
}

register_css_property('right', new CSSRight);

?>