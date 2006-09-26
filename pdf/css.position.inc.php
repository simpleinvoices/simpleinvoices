<?php
// $Header: /cvsroot/html2ps/css.position.inc.php,v 1.9 2006/04/16 16:54:57 Konstantin Exp $

define('POSITION_STATIC',0);
define('POSITION_RELATIVE',1);
define('POSITION_ABSOLUTE',2);
define('POSITION_FIXED',3);

class CSSPosition extends CSSProperty {
  function CSSPosition() { $this->CSSProperty(false, false); }

  function default_value() { return POSITION_STATIC; }

  function parse($value) {
    // As usual, though standards say that CSS properties should be lowercase, 
    // some people make them uppercase. As we're pretending to be tolerant,
    // we need to convert it to lower case

    switch (strtolower($value)) {
    case "absolute":
      return POSITION_ABSOLUTE;
    case "relative":
      return POSITION_RELATIVE;
    case "fixed":
      return POSITION_FIXED;
    case "static":
      return POSITION_STATIC;
    default:
      return POSITION_STATIC;
    }
  }
}

register_css_property('position', new CSSPosition);

?>