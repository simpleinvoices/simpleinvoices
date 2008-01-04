<?php
// $Header: /cvsroot/html2ps/css.border.collapse.inc.php,v 1.6 2006/04/16 16:54:56 Konstantin Exp $

define('BORDER_COLLAPSE', 1);
define('BORDER_SEPARATE', 2);

class CSSBorderCollapse extends CSSProperty {
  function CSSBorderCollapse() { $this->CSSProperty(true, true); }

  function default_value() { return BORDER_SEPARATE; }

  function parse($value) {
    if ($value === 'collapse') { return BORDER_COLLAPSE; };
    if ($value === 'separate') { return BORDER_SEPARATE; };
    return $this->default_value();
  }
}

register_css_property('border-collapse', new CSSBorderCollapse);

?>