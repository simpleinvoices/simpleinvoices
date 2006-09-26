<?php
// $Header: /cvsroot/html2ps/css.bottom.inc.php,v 1.3 2005/12/13 18:24:11 Konstantin Exp $

class CSSBottom extends CSSProperty {
  function CSSBottom() { $this->CSSProperty(false, false); }
  function default_value() { return null; }
  function parse($value) { return $value; }
}

register_css_property('bottom', new CSSBottom);

?>