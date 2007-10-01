<?php
// $Header: /cvsroot/html2ps/css.left.inc.php,v 1.4 2006/02/18 13:28:52 Konstantin Exp $

class CSSLeft extends CSSProperty {
  function CSSLeft() { $this->CSSProperty(false, false); }
  function default_value() { return null; }
  function parse($value) { return $value; }
}

register_css_property('left', new CSSLeft);

?>