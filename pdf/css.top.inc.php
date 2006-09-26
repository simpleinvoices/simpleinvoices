<?php
// $Header: /cvsroot/html2ps/css.top.inc.php,v 1.9 2006/02/18 13:28:52 Konstantin Exp $

// Format of 'top' value:
// array( float, is_percentage )

class CSSTop extends CSSProperty {
  function CSSTop() { $this->CSSProperty(false, false); }
  function default_value() { return null; }
  function parse($value) { return $value; }
}

register_css_property('top', new CSSTop);

?>