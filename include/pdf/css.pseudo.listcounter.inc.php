<?php
// $Header: /cvsroot/html2ps/css.pseudo.listcounter.inc.php,v 1.2 2005/08/02 15:11:47 Konstantin Exp $

class CSSPseudoListCounter extends CSSProperty {
  function CSSPseudoListCounter() { $this->CSSProperty(true, false); }
  function default_value() { return 1; }
}

register_css_property('-list-counter', new CSSPseudoListCounter);

?>